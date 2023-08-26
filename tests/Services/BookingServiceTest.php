<?php

namespace Services;

use ONSBKS_Slots\Includes\Converter\ProductTemplateConverter;
use ONSBKS_Slots\Includes\Models\ProductTemplate;
use ONSBKS_Slots\Includes\Models\Slot;
use ONSBKS_Slots\Includes\Models\SlotCol;
use ONSBKS_Slots\Includes\Models\SlotRow;
use ONSBKS_Slots\RestApi\Exceptions\NotBookableException;
use ONSBKS_Slots\RestApi\Exceptions\NotValidBookingTemplate;
use ONSBKS_Slots\RestApi\Repositories\BookingRepository;
use ONSBKS_Slots\RestApi\Repositories\ProductRepository;
use ONSBKS_Slots\RestApi\Services\BookingService;
use PHPUnit\Framework\TestCase;

class BookingServiceTest extends TestCase
{

	private array $slotArg;
	private array $productTemplateArg;
	private BookingService $bookingService;
	private ProductRepository $productRepository;

	/**
	 * @var ProductRepository|\PHPUnit\Framework\MockObject\MockObject
	 */
	private $productRepositoryMock;

	public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->bookingService = new BookingService();
	    $this->productRepositoryMock = $this->createMock(ProductRepository::class);
		$this->bookingService->productRepository = $this->productRepositoryMock;

		$this->slotArg = [
			'gutter' => 8,
			'vGutter' => 8,
			'rows' => [
				[
					'header' => 'asd',
					'description' => 'asd',
					'showToolTip' => false,
					'cols' => [
						[
							'product_id' => '1',
							'content' => 'Content',
							'show' => true,
							'available_slots' => 5,
							'checked' => false,
							'booked' => 2,
							'expires_in' => '',
							'book' => 1,
						],
						[
							'product_id' => '1',
							'content' => 'Content',
							'show' => true,
							'available_slots' => 7,
							'checked' => false,
							'booked' => 2,
							'expires_in' => '',
							'book' => 1,
						],
						[
							'product_id' => '1',
							'content' => 'NO_BOOK',
							'show' => true,
							'available_slots' => 10,
							'checked' => false,
							'booked' => 2,
							'expires_in' => '',
							'book' => 0,
						],
						[
							'product_id' => '1',
							'content' => 'EXACT_BOOKED',
							'show' => true,
							'available_slots' => 2,
							'checked' => false,
							'booked' => 2,
							'expires_in' => '',
							'book' => 2,
						],
						[
							'product_id' => '1',
							'content' => 'EXCEED_BOOKED',
							'show' => true,
							'available_slots' => 2,
							'checked' => false,
							'booked' => 2,
							'expires_in' => '',
							'book' => 5,
						]
					]
				]
			],
			'allowedBookingPerPerson' => 100,
			'total' => 0
		];
		$this->productTemplateArg = [
			'id' => 1,
			'product_id' => 1,
			'key' => '2023-08-27',
			'template' => $this->slotArg
		];
    }

    public function test_isValidTemplate_success()
    {
        // GIVEN
        $productTemplate = new ProductTemplate($this->productTemplateArg);

        $isValid = $this->bookingService->isValidTemplate($productTemplate, true);

        $this->assertTrue($isValid);
    }

	public function test_isValidTemplate_failed()
	{
		// GIVEN
		$productTemplate = new ProductTemplate($this->productTemplateArg);
		$productTemplate->setId(0);

		$isValid = $this->bookingService->isValidTemplate($productTemplate);
		self::assertFalse($isValid);

	}

	public function test_totalBooked_success()
	{
		// GIVEN
		$slot = new Slot($this->slotArg);

		// THEN
		$actualBooked = $this->bookingService->totalBooked($slot);

		// VERIFY
		$this->assertEquals( 9, $actualBooked );
	}

	public function test_isAllowedBooking()
	{
		// GIVEN
		$slot = new Slot($this->slotArg);

		// THEN
		$actualBooked = $this->bookingService->totalBooked($slot);
		$isBookable = $this->bookingService->isAllowedBooking($slot, $actualBooked);

		// VERIFY
		self::assertTrue($isBookable);
	}

	public function test_isPastDate_success()
	{
		// GIVEN
		$productTemplate = new ProductTemplate($this->productTemplateArg);
		$bookingDate = $productTemplate->getKey();
		$today = current_time('Y-m-d', 1);

		// THEN
		$isBookable = $this->bookingService->isPastDate($bookingDate);
		$isBookableToday = $this->bookingService->isPastDate($today);

		// VERIFY
		self::assertTrue($isBookable);
		self::assertTrue($isBookableToday);
	}

	public function test_isPastDate_failed()
	{
		// GIVEN
		$yesterdayTimestamp = strtotime('yesterday');
		$yesterday = wp_date("Y-m-d", $yesterdayTimestamp);

		// THEN
		$isPastDate = $this->bookingService->isPastDate($yesterday);

		// VERIFY
		self::assertFalse($isPastDate);
	}

	public function test_isBookable_success()
	{
		// GIVEN
		$productTemplate = new ProductTemplate($this->productTemplateArg);
		$bookingDate = $productTemplate->getKey();
		$realProductSlot = new Slot($this->slotArg);
		$realProductSlot->getRows()[0]->getCols()[0]->setBooked(3);
		$realProductSlot->getRows()[0]->getCols()[0]->setAvailableSlots(4);

		// MOCK
		$this->productRepositoryMock->expects(self::once())
			->method("get_product_slot")
			->with($productTemplate->getId(), $bookingDate)
			->willReturn($realProductSlot);

		// THEN
		$isBookable = $this->bookingService->isBookable($productTemplate);

		// VERIFY
		self::assertTrue($isBookable);
	}

	public function test_isBookable_failed()
	{
		// GIVEN
		$productTemplate = new ProductTemplate($this->productTemplateArg);
		$bookingDate = $productTemplate->getKey();

		// MOCK
		$this->productRepositoryMock->expects(self::once())
		                            ->method("get_product_slot")
		                            ->with($productTemplate->getId(), $bookingDate)
		                            ->willReturn(null);

		// THEN
		$isBookable = $this->bookingService->isBookable($productTemplate);

		// VERIFY
		 self::assertFalse($isBookable);
	}

	public function test_processAndModifyTemplate_success()
	{
		// GIVEN
		$col1Booked = 3;
		$col1Book = 1;
		$col1AvailableSlots = 4;

		$productTemplate = new ProductTemplate($this->productTemplateArg);

		$realProductSlot = new Slot($this->slotArg);
		$realProductSlot->getRows()[0]->getCols()[0]->setBooked($col1Booked);
		$realProductSlot->getRows()[0]->getCols()[0]->setBook($col1Book);
		$realProductSlot->getRows()[0]->getCols()[0]->setAvailableSlots($col1AvailableSlots);
		$productTemplate->setTemplate($realProductSlot);

		$col4Book = $productTemplate->getTemplate()->getRows()[0]->getCols()[3]->getBook();
		$col4FinalBooked = $productTemplate->getTemplate()->getRows()[0]->getCols()[3]->getBooked() + $col4Book;
		$col4FinalAvailableSlot = $productTemplate->getTemplate()->getRows()[0]->getCols()[3]->getAvailableSlots() - $col4Book;

		$col5FinalAvailableSlot = 0;
		$col5FinalBooked = $productTemplate->getTemplate()->getRows()[0]->getCols()[4]->getBooked() + $productTemplate->getTemplate()->getRows()[0]->getCols()[4]->getAvailableSlots();
		$col5Book = $productTemplate->getTemplate()->getRows()[0]->getCols()[4]->getAvailableSlots();

		// MOCK
		$this->productRepositoryMock->expects(self::once())
			->method("get_product_slot")
			->with($productTemplate->getId(), $productTemplate->getKey())
			->willReturn($realProductSlot);

		// THEN
		$updatedProductTemplate = $this->bookingService->processAndModifyTemplate($productTemplate);


		// VERIFY
		self::assertInstanceOf(ProductTemplate::class, $updatedProductTemplate);
		// self::assertNotEquals($productTemplate, $updatedProductTemplate);

		// no of new bookings and available slots of first col
		self::assertEquals( $col1Book, $updatedProductTemplate->getTemplate()->getRows()[0]->getCols()[0]->getBook() );
		self::assertEquals( ($col1AvailableSlots - $col1Book), $updatedProductTemplate->getTemplate()->getRows()[0]->getCols()[0]->getAvailableSlots() );
		self::assertEquals( ($col1Booked + $col1Book), $updatedProductTemplate->getTemplate()->getRows()[0]->getCols()[0]->getBooked() );
		self::assertTrue($updatedProductTemplate->getTemplate()->getRows()[0]->getCols()[0]->getChecked());

		// third element no book
		self::assertEquals("NO_BOOK", $updatedProductTemplate->getTemplate()->getRows()[0]->getCols()[2]->getContent());
		self::assertFalse($updatedProductTemplate->getTemplate()->getRows()[0]->getCols()[2]->getChecked());

		// forth col exact book
		self::assertEquals($col4Book, $updatedProductTemplate->getTemplate()->getRows()[0]->getCols()[3]->getBook());
		self::assertEquals($col4FinalBooked, $updatedProductTemplate->getTemplate()->getRows()[0]->getCols()[3]->getBooked());
		self::assertEquals($col4FinalAvailableSlot, $updatedProductTemplate->getTemplate()->getRows()[0]->getCols()[3]->getAvailableSlots());

		// fifth col exceed book
		self::assertEquals($col5Book, $updatedProductTemplate->getTemplate()->getRows()[0]->getCols()[4]->getBook());
		self::assertEquals($col5FinalBooked, $updatedProductTemplate->getTemplate()->getRows()[0]->getCols()[4]->getBooked());
		self::assertEquals($col5FinalAvailableSlot, $updatedProductTemplate->getTemplate()->getRows()[0]->getCols()[4]->getAvailableSlots());
	}

	public function test_updateProductSlot_success()
	{
		// GIVEN
		$productTemplate = new ProductTemplate( $this->productTemplateArg );

		$slot = new Slot( $productTemplate->getTemplate() );
		$slot->getRows()[0]->getCols()[0]->setBook(3);
		foreach ($slot->getRows() as $rowKey => $row )
		{
			foreach ($row->getCols() as $colKey => $col ) {
				$col->setBook(0);
			}
		}

		// MOCK
		$this->productRepositoryMock->expects(self::once())
		                            ->method("getFormattedDate")
		                            ->willReturn("NML_2023-08-27");

		// THEN
		$this->bookingService->updateProductSlot($productTemplate);

			// VERIFY
		self::assertEquals(0, $slot->getRows()[0]->getCols()[0]->getBook());
	}

	public function test_productTemplateToBookingModel_success()
	{
		self::assertTrue(true);
	}
}
