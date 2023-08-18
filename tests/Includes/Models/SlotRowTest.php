<?php

namespace Includes\Models;

use ONSBKS_Slots\Includes\Models\SlotCol;
use ONSBKS_Slots\Includes\Models\SlotRow;
use PHPUnit\Framework\TestCase;

class SlotRowTest extends TestCase
{

    public function test_SlotRowFromArray()
    {
        // GIVEN
        $colArg = [
            'product_id' => '1',
            'content' => 'Content',
            'show' => true,
            'available_slots' => 2,
            'checked' => false,
            'booked' => 2,
            'expires_in' => '',
            'book' => 2,
        ];
        $rowArg = [
            'header' => 'test_header',
            'description' => 'asd',
            'showToolTip' => false,
            'cols' => [
                $colArg
            ]
        ];

        // THEN
        $slotCol = new SlotCol($colArg);
        $slotRow = new SlotRow($rowArg);

        // VERIFY
        self::assertEquals('test_header', $slotRow->getHeader());
        self::assertEquals($rowArg, $slotRow->getData());
        self::assertCount(1, $slotRow->getCols());
        self::assertEquals($slotCol, $slotRow->getCols()[0]);
        self::assertEquals(2, $slotRow->getCols()[0]->getBook());

    }

    public function test_SlotRowFromObject()
    {
        // GIVEN
        $colArg = [
            'product_id' => '1',
            'content' => 'Content',
            'show' => true,
            'available_slots' => 2,
            'checked' => false,
            'booked' => 2,
            'expires_in' => '',
            'book' => 2,
        ];
        $rowArg = [
            'header' => 'test_header',
            'description' => 'asd',
            'showToolTip' => false,
            'cols' => [
                $colArg
            ]
        ];

        // THEN
        $slotCol1 = new SlotCol($colArg);
        $slotRow1 = new SlotRow($rowArg);

        $slotRow2 = new SlotRow($slotRow1);

        // VERIFY
        self::assertEquals($slotRow1, $slotRow2);
        self::assertEquals($slotCol1, $slotRow2->getCols()[0]);
        self::assertEquals($slotCol1, $slotRow2->getCols()[0]);
    }
}
