<?php

namespace ONSBKS_Slots\RestApi\Services;

use ONSBKS_Slots\Includes\Converter\ProductTemplateConverter;
use ONSBKS_Slots\Includes\Models\BookingModel;
use ONSBKS_Slots\Includes\Models\ProductTemplate;
use ONSBKS_Slots\Includes\Models\Slot;
use ONSBKS_Slots\RestApi\Exceptions\BookingCreateException;
use ONSBKS_Slots\RestApi\Exceptions\BookingFailedException;
use ONSBKS_Slots\RestApi\Exceptions\BookingNotAllowedException;
use ONSBKS_Slots\RestApi\Exceptions\BookingNotFound;
use ONSBKS_Slots\RestApi\Exceptions\BookingProcessException;
use ONSBKS_Slots\RestApi\Exceptions\ConversionException;
use ONSBKS_Slots\RestApi\Exceptions\NoFingerPrintException;
use ONSBKS_Slots\RestApi\Exceptions\NotBookableException;
use ONSBKS_Slots\RestApi\Exceptions\NotValidBookingTemplate;
use ONSBKS_Slots\RestApi\Exceptions\UnauthorizedException;
use ONSBKS_Slots\RestApi\Repositories\BookingRepository;
use ONSBKS_Slots\RestApi\Repositories\ProductRepository;
use PHPUnit\Exception;
use Prophecy\Doubler\ClassPatch\ThrowablePatch;


class BookingService
{

    public BookingRepository $bookingRepository;
    public ProductRepository $productRepository;
    public ProductTemplateConverter $productTemplateConverter;

    public function __construct()
    {
        $this->bookingRepository        = new BookingRepository();
        $this->productRepository        = new ProductRepository();
        $this->productTemplateConverter = new ProductTemplateConverter();
    }


    /**
     * @param array $query [per_page, paged]
     * @return array
     */
    public function findAll(array $query): array
    {
        $per_page = $query['per_page'] ?? 10;
        $paged = $query['paged'] ?? 1;

        return $this->bookingRepository->findAll($per_page, $paged);
    }


    /**
     * @throws UnauthorizedException
     */
    public function findAllBookingsByUserId(bool $isAnonymousUser, string $userId): array
    {
        if($isAnonymousUser) throw new UnauthorizedException();

        return $this->bookingRepository->findAllByUserId($userId);
    }


    public function findAllByUserIdOrFingerPrint(int $userId, string $fingerPrint, int $per_page, int $paged): array
    {
        return $this->bookingRepository->findAllByUserIdOrFingerPrint($userId, $fingerPrint, $per_page, $paged);
    }


    /**
     * @param int $booking_id
     * @param bool $throwable
     * @return BookingModel|null
     * @throws BookingNotFound
     */
    public function findBookingByBookingId(int $booking_id, bool $throwable = false): ?BookingModel
    {
        $booking = $this->bookingRepository->findById($booking_id);

        if(!$booking && $throwable) throw new BookingNotFound();

        return $booking;
    }


    /**
     * @throws BookingNotFound
     */
    public function updateBookingByBookingId(mixed $bookingId, array $data)
    {
        $booking = $this->findBookingByBookingId($bookingId, true);
        $modifyBooking = array_merge($booking->getData(), $data);

        //  make possibilities to change the booking from here
    }


    /**
     * @throws NotBookableException
     * @throws NotValidBookingTemplate
     * @throws BookingNotAllowedException
     * @throws BookingFailedException
     * @throws NoFingerPrintException
     * @throws BookingCreateException
     * @throws BookingProcessException
     * @throws ConversionException
     */
    public function createBooking(ProductTemplate $productTemplate, int $userId, string $fingerPrint ): BookingModel
    {
        $insertId     = 0;
        $bookingDate  = $productTemplate->getKey();
        $productId    = $productTemplate->getProductId();

        // validate finger print
        $this->isValidFingerPrint( $fingerPrint, true );

        // if the product_id really exists !
        $this->isValidTemplate( $productTemplate, true );

        // we extract the total booking count and validate it.
        $totalBooked = $this->totalBooked( $productTemplate->getTemplate() );
        $this->isAllowedBooking( $productTemplate->getTemplate(), $totalBooked, true );

        // verify invalid date for past dates
        $this->isPastDate( $bookingDate, true );

        // verify with db, invalid date, cross booking
        $this->isBookable( $productTemplate, true );

        // cross verify by the booking slots availability
        $updatedProductTemplate = $this->processAndModifyTemplate($productTemplate, true);

        // update the product meta slot after above calculation is done.
        $this->updateProductSlot($updatedProductTemplate, true);

        // setting booking model properties
        $bookingModel = $this->productTemplateToBookingModel($updatedProductTemplate);
        $bookingModel->setUserId($userId);
        $bookingModel->setFingerPrint($fingerPrint);

        // create booking in database
        $insertId = $this->createBookingInDB($bookingModel);
        $bookingModel->setId($insertId);

        return $bookingModel;
    }

    /**
     * @throws NoFingerPrintException
     */
    private function isValidFingerPrint(string $fingerPrint, bool $throwable = false): bool
    {
        if(!$fingerPrint && !$throwable) return false;

        if(!$fingerPrint) throw new NoFingerPrintException();

        return true;
    }

    public function totalBooked(Slot $template): int
    {
        $totalBooked = 0;
        foreach ( $template->getRows() as $row )
        {
            foreach ( $row->getCols() as $col )
            {
                $totalBooked += $col->getBook();
            }
        }
        return $totalBooked;
    }

    /**
     * @param Slot $template
     * @param $booked
     * @param bool $throwable
     * @return bool
     * @throws BookingNotAllowedException
     */
    public function isAllowedBooking(Slot $template, $booked, bool $throwable = false): bool
    {
        if( $template->getAllowedBookingPerPerson() >= $booked ) return true;

        if($throwable) throw new BookingNotAllowedException("Booking is not allowed, exceeded maximum booking");

        return false;
    }

    private function crossBookingValidation( Slot $template ): Slot
    {
        return $template;
    }

    /**
     * Invalid Past date
     * @param string $bookingDate
     * @param bool $throwable
     * @return bool
     * @throws NotBookableException
     */
    public function isPastDate(string $bookingDate, bool $throwable = false): bool
    {
        $bookingDateTimestamp = strtotime($bookingDate);
        $today = current_time( 'Y-m-d', 1 );
		$startingTime = strtotime($today);

        // we can first check that if it is before today or not
        if($startingTime > $bookingDateTimestamp && !$throwable) return false;
        if($startingTime > $bookingDateTimestamp) throw new NotBookableException("Not Bookable, as the Date is not valid");

        return true;
    }

    /**
     * verify the date if it is valid ot not.
     * @throws NotBookableException
     */
    public function isBookable(ProductTemplate $productTemplate, bool $throwable = false): bool
    {
        $bookingDate = $productTemplate->getKey();
        // then isValid then we cross verify by fetching the product_meta of the day that the template is referring
        $realProductSlot = $this->productRepository->get_product_slot( $productTemplate->getProductId(), $bookingDate );

        if(!$realProductSlot && !$throwable) return false;

        if(!$realProductSlot) throw new NotBookableException("Not Bookable, as the Date does not exist for booking");

        // return true if the date is valid;
        return true;
    }



    /**
     * @throws NotValidBookingTemplate
     */
    public function isValidTemplate(ProductTemplate $productTemplate, bool $throwable = false): bool
    {
        if($productTemplate->getId()) return true;

        if($throwable) throw new NotValidBookingTemplate("The Booking template is corrupted");

        return false;
    }

    /**
     * @throws BookingProcessException
     */
    public function processAndModifyTemplate(ProductTemplate $productTemplate, bool $throwable = false): ProductTemplate
    {
        try{
            $bookingDate = $productTemplate->getKey();

            // we cross verify by fetching the product_meta of the day that the template is referring
            $realProductSlot = $this->productRepository->get_product_slot( $productTemplate->getProductId(), $bookingDate );

//        $realKey = $this->productRepository->get_product_key( $productTemplate->getProductId(), $bookingDate );

            $bookingProductTemplate = new ProductTemplate($productTemplate);
//        $bookingProductTemplate->setKey($realKey);
            // loop upto iterate the cols, identify the rowIndex and the colIndex
            foreach ( $bookingProductTemplate->getTemplate()->getRows() as $rowKey => $row )
            {
                foreach ($row->getCols() as $colKey => $col)
                {
                    $availableSlots = $realProductSlot->getRows()[$rowKey]->getCols()[$colKey]->getAvailableSlots();
                    $totalBooked = $realProductSlot->getRows()[$rowKey]->getCols()[$colKey]->getBooked();
                    $totalBook = $col->getBook();
                    if( $totalBook > 0 ){
                        $col->setChecked(true);
                        if($totalBook > $availableSlots ){
                            $totalBook = $availableSlots;
                            $availableSlots = 0;
                        } else {
                            $availableSlots = $availableSlots - $totalBook;
                        }
                        $totalBooked = $totalBooked + $totalBook;

                        // set all new values
                        $col->setBook( $totalBook );
                        $bookingProductTemplate->getTemplate()->getRows()[$rowKey]->getCols()[$colKey]->setBook($totalBook);
                        $col->setBooked( $totalBooked );
                        $bookingProductTemplate->getTemplate()->getRows()[$rowKey]->getCols()[$colKey]->setBooked($totalBooked);
                        $col->setAvailableSlots( $availableSlots );
                        $bookingProductTemplate->getTemplate()->getRows()[$rowKey]->getCols()[$colKey]->setAvailableSlots($availableSlots);
                    }
                    else {
                        $col->setChecked(false);
                    }
                }
            }
            return $bookingProductTemplate;
            // each cols: if book is present in testPT, proceed next
            // realPT->availableSlot >= testPT->book
            // if false, update the realPT booked with residual and update the testPT book with residual and return it.
        }
        catch (Exception $e) {
            throw new BookingProcessException();
        }
    }


    /**
     * @throws BookingFailedException
     */
    public function updateProductSlot(ProductTemplate $updatedProductTemplate, bool $throwable = false): bool
    {
        $productId = $updatedProductTemplate->getProductId();
        $date = $this->productRepository->getFormattedDate( $updatedProductTemplate->getKey() );
        $updatedSlot = $updatedProductTemplate->getTemplate();

        foreach ( $updatedProductTemplate->getTemplate()->getRows() as $rowKey => $row )
        {
            foreach ($row->getCols() as $colKey => $col )
            {
                // prepare the template for next orders.
                if($col->getChecked()) {
                    $updatedSlot->getRows()[$rowKey]->getCols()[$colKey]->setChecked(false);
                    $updatedSlot->getRows()[$rowKey]->getCols()[$colKey]->setBook(0);
                }
            }
        }

        $success = update_post_meta($productId, $date, $updatedSlot->getData());

        if(!$success && $throwable) throw new BookingFailedException("Booking Failed, Please Contact Support");

        return $success;
    }

    /**
     * convert the product template to BookingModel
     *
     * @param ProductTemplate $updatedProductTemplate
     *
     * @return BookingModel
     * @throws ConversionException
     */
	public function productTemplateToBookingModel( ProductTemplate $updatedProductTemplate ): BookingModel
	{
		return $this->productTemplateConverter->toBookingModel($updatedProductTemplate);
	}

    /**
     * Insert booking in the database
     *
     * @param BookingModel $bookingModel
     *
     * @return int
     * @throws BookingCreateException
     */
	public function createBookingInDB(BookingModel $bookingModel): int
	{
        try {
            return $this->bookingRepository->createBooking($bookingModel);
        } catch (Exception $e) {
            throw new BookingCreateException();
        }
	}
}