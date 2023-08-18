<?php

namespace Services;

use ONSBKS_Slots\Includes\Converter\ProductTemplateConverter;
use ONSBKS_Slots\Includes\Models\ProductTemplate;
use ONSBKS_Slots\Includes\Models\Slot;
use ONSBKS_Slots\Includes\Models\SlotCol;
use ONSBKS_Slots\Includes\Models\SlotRow;
use ONSBKS_Slots\RestApi\Repositories\BookingRepository;
use ONSBKS_Slots\RestApi\Repositories\ProductRepository;
use ONSBKS_Slots\RestApi\Services\BookingService;
use PHPUnit\Framework\TestCase;

class BookingServiceTest extends TestCase
{

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->bookingRepository        = $this->createMock('\ONSBKS_Slots\RestApi\Repositories\BookingRepository');
        $this->productRepository        = $this->createMock('\ONSBKS_Slots\RestApi\Repositories\ProductRepository');
        $this->productTemplateConverter = $this->createMock('\ONSBKS_Slots\Includes\Converter\ProductTemplateConverter');

        $this->bookingService = new BookingService();

    }

    public function test_start(){
        self::assertTrue(true);
    }

    private function totalBooked_success(){
        // GIVEN
        $slot = $this->getSlot();
        // THEN
        $totalBooked = $this->bookingService->totalBooked($slot);

        // VERIFY
        $this->assertEquals($totalBooked, 3);
    }

    public function test_isValidTemplate_success() {
        // GIVEN
        $productTemplate = $this->getProductTemplate();

        $isValid = $this->bookingService->isValidTemplate($productTemplate, true);

        $this->assertTrue($isValid);
    }




    // HELPERS
    private function getSlot(): Slot
    {
        $slotCol1 = new SlotCol([
            'product_id' => '1',
            'content' => 'Content',
            'show' => true,
            'available_slots' => 7,
            'checked' => false,
            'booked' => 2,
            'expires_in' => '',
            'book' => 1,
        ]);

        $slotCol2 = new SlotCol([
            'product_id' => '1',
            'content' => 'Content',
            'show' => true,
            'available_slots' => 2,
            'checked' => false,
            'booked' => 2,
            'expires_in' => '',
            'book' => 2,
        ]);

        $slotRow = new SlotRow([
            'header' => '',
            'description' => '',
            'showToolTip' => false,
            'cols' => [$slotCol1->getData(), $slotCol2->getData()]
        ]);
        return new Slot([
            'gutter' => 8,
            'vGutter' => 8,
            'rows' => [$slotRow->getData()],
            'allowedBookingPerPerson' => 100,
            'total' => 0
        ]);
    }

    private function getProductTemplate(): ProductTemplate
    {

//        return new ProductTemplate([
//            'id' => 0,
//            'product_id' => 0,
//            'key' => '',
//            'template' => new Slot()
//        ]);
        return new ProductTemplate([
            'id' => 1,
            'product_id' => 1,
            'key' => 'MON',
            'template' => [
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
                                'available_slots' => 2,
                                'checked' => false,
                                'booked' => 2,
                                'expires_in' => '',
                                'book' => 2,
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
                            ]
                        ]
                    ]
                ],
                'allowedBookingPerPerson' => 100,
                'total' => 0
            ]
        ]);
    }
}
