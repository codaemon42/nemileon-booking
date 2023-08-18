<?php

namespace Includes\Models;

use ONSBKS_Slots\Includes\Models\Slot;
use ONSBKS_Slots\Includes\Models\SlotCol;
use ONSBKS_Slots\Includes\Models\SlotRow;
use PHPUnit\Framework\TestCase;

class SlotTest extends TestCase
{

    private array $colArg;
    private array $rowArg;
    private array $slotArg;

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
    }

    public function test_SlotFromArray()
    {
        // GIVEN
        $slot = new Slot($this->slotArg);

        // THEN
        $slotCol = new SlotCol($this->colArg);
        $slotRow = new SlotRow($this->rowArg);

        // VERIFY
        self::assertEquals($this->slotArg, $slot->getData());
        self::assertEquals($slotRow, $slot->getRows()[0]);
        self::assertEquals($slotCol, $slot->getRows()[0]->getCols()[0]);

    }

    public function test_SlotFromObject()
    {
        // GIVEN
        $oldSlot = new Slot($this->slotArg);

        // THEN
        $slot = new Slot($oldSlot);
        $slotCol = new SlotCol($this->colArg);
        $slotRow = new SlotRow($this->rowArg);

        // VERIFY
        self::assertEquals($this->slotArg, $slot->getData());
        self::assertEquals($slotRow, $slot->getRows()[0]);
        self::assertEquals($slotCol, $slot->getRows()[0]->getCols()[0]);
    }
}
