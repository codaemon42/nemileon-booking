<?php

namespace Includes\Models;

use ONSBKS_Slots\Includes\Models\BookingModel;
use ONSBKS_Slots\Includes\Models\Slot;
use ONSBKS_Slots\Includes\Models\SlotCol;
use ONSBKS_Slots\Includes\Models\SlotRow;
use ONSBKS_Slots\Includes\Status\BookingStatus;
use PHPUnit\Framework\TestCase;

class BookingModelTest extends TestCase
{
    private array $colArg;
    private array $rowArg;
    private array $slotArg;
    private array $bookingModelArg;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->colArg = [
            'product_id' => '1',
            'content' => 'Content',
            'show' => true,
            'available_slots' => 2,
            'checked' => false,
            'booked' => 2,
            'expires_in' => '',
            'book' => 2,
        ];

        $this->rowArg = [
            'header' => 'test_header',
            'description' => 'asd',
            'showToolTip' => false,
            'cols' => [
                $this->colArg
            ]
        ];

        $this->slotArg = [
            'gutter' => 8,
            'vGutter' => 8,
            'rows' => [
                $this->rowArg
            ],
            'allowedBookingPerPerson' => 100,
            'total' => 0
        ];
        $this->bookingModelArg = [
            'id' => 0,
            'user_id' => '',
            'finger_print' => 'test_finger_print',
            'name' => '',
            'booking_date' => '',
            'seats' => '',
            'product_id' => 0,
            'headers' => '',
            'top_header' => '',
            'total_price' => 0,
            'status' => BookingStatus::PENDING_PAYMENT,
            'template' => $this->slotArg
        ];
    }

	/**
	 * @throws \ONSBKS_Slots\RestApi\Exceptions\InvalidBookingStatusException
	 */
	public function test_BookingModelFromArray()
    {
        // GIVEN
        $bookingModel = new BookingModel($this->bookingModelArg);

        // THEN
        $slotCol = new SlotCol($this->colArg);
        $slotRow = new SlotRow($this->rowArg);
        $slot = new Slot($this->slotArg);

        // VERIFY
        self::assertEquals($this->bookingModelArg, $bookingModel->getData());
        self::assertEquals($slot, $bookingModel->getTemplate());
        self::assertEquals(SlotRow::List([$slotRow]), $bookingModel->getTemplate()->getRows());
        self::assertEquals(SlotCol::List([$slotCol]), $bookingModel->getTemplate()->getRows()[0]->getCols());

    }

	/**
	 * @throws \ONSBKS_Slots\RestApi\Exceptions\InvalidBookingStatusException
	 */
	public function test_BookingModelFromObject()
    {
        // GIVEN
        $oldBookingModel = new BookingModel($this->bookingModelArg);

        // THEN
        $slotCol = new SlotCol($this->colArg);
        $slotRow = new SlotRow($this->rowArg);
        $slot = new Slot($this->slotArg);
        $bookingModel = new BookingModel($oldBookingModel);

        // VERIFY
        self::assertEquals($oldBookingModel, $bookingModel);
        self::assertEquals($slot, $bookingModel->getTemplate());
        self::assertEquals(SlotRow::List([$slotRow]), $bookingModel->getTemplate()->getRows());
        self::assertEquals(SlotCol::List([$slotCol]), $bookingModel->getTemplate()->getRows()[0]->getCols());
    }
}
