<?php

namespace Includes\Converter;

use ONSBKS_Slots\Includes\Converter\ProductTemplateConverter;
use ONSBKS_Slots\Includes\Models\BookingModel;
use ONSBKS_Slots\Includes\Models\ProductTemplate;
use ONSBKS_Slots\Includes\WooCommerce\BookingSlotProduct;
use PHPUnit\Framework\TestCase;

class ProductTemplateConverterTest extends TestCase
{
    private array $colArg;
    private array $colArg2;
    private array $rowArg;
    private array $rowArg2;
    private array $slotArg;
    private array $productTemplateArg;
    private array $bookingModelArg;
    private int $book = 4;
    private int $book2 = 2;
    private int $productPrice = 10;
    private string $productName = "test_product";
    private string $header = "top_header";
    private string $header2 = "'second_row_header_minor'";


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
            'book' => $this->book,
        ];
        $this->colArg2 = [
            'product_id' => '1',
            'content' => 'Content',
            'show' => true,
            'available_slots' => 2,
            'checked' => false,
            'booked' => 2,
            'expires_in' => '',
            'book' => $this->book2,
        ];

        $this->rowArg = [
            'header' => $this->header,
            'description' => 'asd',
            'showToolTip' => false,
            'cols' => [
                $this->colArg,
                $this->colArg2
            ]
        ];

        $this->rowArg2 = [
            'header' => $this->header2,
            'description' => 'asd',
            'showToolTip' => false,
            'cols' => [
                $this->colArg2
            ]
        ];

        $this->slotArg = [
            'gutter' => 8,
            'vGutter' => 8,
            'rows' => [
                $this->rowArg,
                $this->rowArg2
            ],
            'allowedBookingPerPerson' => 100,
            'total' => 0
        ];

        $this->productTemplateArg = [
            'id' => 1,
            'product_id' => 1,
            'key' => '2023-11-11',
            'template' => $this->slotArg
        ];

        $this->bookingModelArg = [
            'id' => 0,
            'user_id' => '',
            'name' => $this->productName,
            'booking_date' => '2023-11-11',
            'seats' => $this->book + $this->book2 + $this->book2,
            'product_id' => 1,
            'headers' => "$this->header, $this->header2",
            'top_header' => $this->header,
            'total_price' => $this->productPrice * ($this->book + $this->book2 + $this->book2),
            'template' => $this->slotArg
        ];
    }


    public function test_ToBookingModel()
    {
        // GIVEN
        $productTemplate = new ProductTemplate($this->productTemplateArg);
        $bookingModel = new BookingModel($this->bookingModelArg);

        $bookingSlotProduct = $this->createMock(BookingSlotProduct::class);
        $bookingSlotProduct->method("get_title")->willReturn($this->productName);
        $bookingSlotProduct->method("get_price")->willReturn($this->productPrice);

        $productTemplateConverterMock = $this->getMockBuilder(ProductTemplateConverter::class)
            ->disableProxyingToOriginalMethods()
            ->setMethodsExcept(['toBookingModel'])
            ->getMock();
        $productTemplateConverterMock->method('getProduct')->willReturn($bookingSlotProduct);

        // THEN
        $actualBookingModel = $productTemplateConverterMock->toBookingModel($productTemplate);

        // VERIFY
        self::assertEquals($bookingModel, $actualBookingModel);

    }
}
