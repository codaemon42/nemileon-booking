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
	private array $realSlotArg;
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
	    $this->realSlotArg = [
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
						    'available_slots' => 6,
						    'checked' => false,
						    'booked' => 2,
						    'expires_in' => '',
						    'book' => 0,
					    ],
					    [
						    'product_id' => '1',
						    'content' => 'Content',
						    'show' => true,
						    'available_slots' => 8,
						    'checked' => false,
						    'booked' => 2,
						    'expires_in' => '',
						    'book' => 0,
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
						    'available_slots' => 4,
						    'checked' => false,
						    'booked' => 2,
						    'expires_in' => '',
						    'book' => 0,
					    ],
					    [
						    'product_id' => '1',
						    'content' => 'EXCEED_BOOKED',
						    'show' => true,
						    'available_slots' => 2,
						    'checked' => false,
						    'booked' => 2,
						    'expires_in' => '',
						    'book' => 0,
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
		$productTemplate = new ProductTemplate($this->productTemplateArg);

		$realProductSlot = new Slot($this->realSlotArg);

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
		$expectedFirstCol = $productTemplate->getTemplate()->getRows()[0]->getCols()[0];
		$actualFirstCol = $updatedProductTemplate->getTemplate()->getRows()[0]->getCols()[0];

		self::assertTrue($actualFirstCol->getChecked());
		self::assertEquals(1, $actualFirstCol->getBook());
		self::assertEquals(3 , $actualFirstCol->getBooked());
		self::assertEquals(5, $actualFirstCol->getAvailableSlots());

		// third element no book
		self::assertEquals("NO_BOOK", $updatedProductTemplate->getTemplate()->getRows()[0]->getCols()[2]->getContent());
		self::assertFalse($updatedProductTemplate->getTemplate()->getRows()[0]->getCols()[2]->getChecked());

		// forth col exact book
		$expectedForthCol = $productTemplate->getTemplate()->getRows()[0]->getCols()[3];
		$actualForthCol = $updatedProductTemplate->getTemplate()->getRows()[0]->getCols()[3];
		self::assertEquals(2, $actualForthCol->getBook());
		self::assertEquals(4, $actualForthCol->getBooked());
		self::assertEquals(2, $actualForthCol->getAvailableSlots());

		// fifth col exceed book
		// $expectedFifthCol = $productTemplate->getTemplate()->getRows()[0]->getCols()[4];
		$actualFifthCol = $updatedProductTemplate->getTemplate()->getRows()[0]->getCols()[4];
		self::assertEquals(2, $actualFifthCol->getBook());
		self::assertEquals(4, $actualFifthCol->getBooked());
		self::assertEquals(0, $actualFifthCol->getAvailableSlots());
	}

	public function test_recycleSlot_success()
	{
		// GIVEN
		$productTemplate = new ProductTemplate($this->productTemplateArg);

		$realProductSlot = new Slot($this->realSlotArg);

		// MOCK
		$this->productRepositoryMock->expects(self::once())
		                            ->method("get_product_slot")
		                            ->with($productTemplate->getId(), $productTemplate->getKey())
		                            ->willReturn($realProductSlot);

		// THEN
		$updatedProductTemplate = $this->bookingService->processAndModifyTemplate($productTemplate);

		// THEN
		$actualSlot = $this->bookingService->recycleSlot($updatedProductTemplate->getTemplate());

		// VERIFY
		self::assertFalse(!$actualSlot);
		self::assertNotEquals($updatedProductTemplate->getTemplate(), $actualSlot);
		self::assertFalse($actualSlot->getRows()[0]->getCols()[0]->getChecked());

		self::assertEquals(0, $actualSlot->getRows()[0]->getCols()[0]->getBook());

		self::assertEquals(0, $actualSlot->getRows()[0]->getCols()[0]->getData()['book']);
	}

	public function test_updateProductSlot_success()
	{
		// GIVEN
		$productTemplate = new ProductTemplate($this->productTemplateArg);

		$realProductSlot = new Slot($this->realSlotArg);

		// MOCK
		$this->productRepositoryMock->expects(self::once())
		                            ->method("get_product_slot")
		                            ->with($productTemplate->getId(), $productTemplate->getKey())
		                            ->willReturn($realProductSlot);

		// THEN
		$updatedProductTemplate = $this->bookingService->processAndModifyTemplate($productTemplate);

		// THEN
		$actualSlot = $this->bookingService->recycleSlot($updatedProductTemplate->getTemplate());


		$this->bookingService->updateProductSlot($updatedProductTemplate, $actualSlot);

		// VERIFY
		self::assertEquals(0, $actualSlot->getRows()[0]->getCols()[0]->getData()['book']);

	}

	public function test_productTemplateToBookingModel_success()
	{
		self::assertTrue(true);
	}
}
