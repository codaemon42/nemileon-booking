<?php

namespace Includes\Models;

use ONSBKS_Slots\Includes\Models\ProductTemplate;
use ONSBKS_Slots\Includes\Models\Slot;
use ONSBKS_Slots\Includes\Models\SlotCol;
use ONSBKS_Slots\Includes\Models\SlotRow;
use PHPUnit\Framework\TestCase;

class ProductTemplateTest extends TestCase
{
    private array $colArg;
    private array $rowArg;
    private array $slotArg;
    private array $productTemplateArg;

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

        $this->productTemplateArg = [
            'id' => 1,
            'product_id' => 1,
            'key' => 'MON',
            'template' => $this->slotArg
        ];

    }

    public function test_ProductTemplateFromArray(){
        // GIVEN
        $productTemplate = new ProductTemplate($this->productTemplateArg);

        // THEN
        $slot = new Slot($this->slotArg);
        $slotRow = new SlotRow($this->rowArg);
        $slotCol = new SlotCol($this->colArg);

        // VERIFY
        self::assertEquals($this->productTemplateArg, $productTemplate->getData());
        self::assertEquals($slot, $productTemplate->getTemplate());
        self::assertEquals(SlotRow::List([$slotRow]), $productTemplate->getTemplate()->getRows());
        self::assertEquals(SlotCol::List([$slotCol]), $productTemplate->getTemplate()->getRows()[0]->getCols());
    }
}
