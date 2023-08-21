<?php

namespace ONSBKS_Slots\RestApi\Services;

use ONSBKS_Slots\Includes\Converter\ProductTemplateConverter;
use ONSBKS_Slots\Includes\Models\BookingModel;
use ONSBKS_Slots\Includes\Models\ProductTemplate;
use ONSBKS_Slots\Includes\Models\Slot;
use ONSBKS_Slots\RestApi\Exceptions\BookingFailedException;
use ONSBKS_Slots\RestApi\Exceptions\BookingNotAllowedException;
use ONSBKS_Slots\RestApi\Exceptions\BookingNotFound;
use ONSBKS_Slots\RestApi\Exceptions\NotBookableException;
use ONSBKS_Slots\RestApi\Exceptions\NotValidBookingTemplate;
use ONSBKS_Slots\RestApi\Repositories\BookingRepository;
use ONSBKS_Slots\RestApi\Repositories\ProductRepository;


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
        $per_page = $query['per_page'] || 10;
        $paged = $query['paged'] || 1;
        return $this->bookingRepository->findAll($per_page, $paged);
    }


    /**
     * @param int $booking_id
     * @param $throwable
     * @return BookingModel|null
     * @throws BookingNotFound
     */
    public function findBookingByBookingId(int $booking_id, $throwable = false): ?BookingModel
    {
        $booking = $this->bookingRepository->findById($booking_id);

        if($throwable) throw new BookingNotFound();

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
     */
    public function createBooking(ProductTemplate $productTemplate ): int
    {
        $insertId     = 0;
        $bookingDate  = $productTemplate->getKey();
        $productId    = $productTemplate->getProductId();

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

        // create booking
        $bookingModel = $this->productTemplateToBookingModel($updatedProductTemplate);
        $insertId = $this->createBookingInDB($bookingModel);

        return $insertId;
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
        if(!$realProductSlot) throw new NotBookableException("Not Bookable, as the Date is not valid");

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

    public function processAndModifyTemplate(ProductTemplate $productTemplate, bool $throwable = false): ProductTemplate
    {
        $bookingDate = $productTemplate->getKey();

        // we cross verify by fetching the product_meta of the day that the template is referring
        $realProductSlot = $this->productRepository->get_product_slot( $productTemplate->getProductId(), $bookingDate );

		$bookingProductTemplate = new ProductTemplate($productTemplate);
        // loop upto iterate the cols, identify the rowIndex and the colIndex
        foreach ( $bookingProductTemplate->getTemplate()->getRows() as $rowKey => $row )
        {
            foreach ($row-> getCols() as $colKey => $col)
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
                    $col->setBooked( $totalBooked );
                    $col->setAvailableSlots( $availableSlots );
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


    /**
     * @throws BookingFailedException
     */
    public function updateProductSlot(ProductTemplate $updatedProductTemplate, bool $throwable = false): bool
    {
        $productId = $updatedProductTemplate->getProductId();
        $dateOrDate = $updatedProductTemplate->getKey();
        $updatedSlot = $updatedProductTemplate->getTemplate();

        foreach ( $updatedProductTemplate->getTemplate()->getRows() as $row )
        {
            foreach ($row-> getCols() as $col)
            {
                // prepare the template for next orders.
                if($col->getChecked()) {
                    $col->setChecked(false);
                    $col->setBook(0);
                }
            }
        }

        $success = update_post_meta($productId, $dateOrDate, $updatedSlot);

        if(!$success && $throwable) throw new BookingFailedException("Booking Failed, Please Contact Support");

        return $success;
    }

	/**
	 * convert the product template to BookingModel
	 *
	 * @param ProductTemplate $updatedProductTemplate
	 *
	 * @return BookingModel
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
	 */
	public function createBookingInDB(BookingModel $bookingModel): int
	{
		return $this->bookingRepository->createBooking($bookingModel);
	}
}