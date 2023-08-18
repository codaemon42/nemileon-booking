<?php

namespace Includes\Models;

use ONSBKS_Slots\Includes\Models\SlotCol;
use PHPUnit\Framework\TestCase;

class SlotColTest extends TestCase
{


    public function test_SlotColFromArray()
    {
        // GIVEN
        $arg = [
            'product_id' => '1',
            'content' => 'Content',
            'show' => true,
            'available_slots' => 7,
            'checked' => false,
            'booked' => 2,
            'expires_in' => '',
            'book' => 1,
        ];

        // THEN
        $slotCol = new SlotCol($arg);

        // VERIFY
        self::assertEquals($slotCol->getData(), $arg);
        self::assertEquals($slotCol->getBook(), $arg['book']);
        self::assertEquals($slotCol->getBooked(), $arg['booked']);
        self::assertEquals($slotCol->getAvailableSlots(), $arg['available_slots']);
    }

    public function test_SlotColFromObject(){
        // GIVEN
        $arg = [
            'product_id' => '1',
            'content' => 'Content',
            'show' => true,
            'available_slots' => 7,
            'checked' => false,
            'booked' => 2,
            'expires_in' => '',
            'book' => 1,
        ];
        $slotColObject = new SlotCol($arg);

        // THEN
        $newSlotCol = new SlotCol($slotColObject);

        // VERIFY
        self::assertEquals($slotColObject, $newSlotCol);
        self::assertEquals($slotColObject->getData(), $newSlotCol->getData());
        self::assertEquals($arg, $newSlotCol->getData());
        self::assertEquals($slotColObject->getAvailableSlots(), $newSlotCol->getAvailableSlots());

        // VERIFY MUTATION
        $newSlotCol->setBook(2);
        self::assertNotEquals($slotColObject, $newSlotCol);
    }
}
