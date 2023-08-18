<?php

namespace ONSBKS_Slots\Includes\Converter;

use ONSBKS_Slots\Includes\Models\BookingModel;
use ONSBKS_Slots\Includes\Models\ProductTemplate;
use ONSBKS_Slots\Includes\Models\Slot;
use ONSBKS_Slots\Includes\WooCommerce\BookingSlotProduct;

class ProductTemplateConverter
{

    /**
     * UnMapped id, user_id
     * @param ProductTemplate $productTemplate
     * @return BookingModel
     */
    public function toBookingModel(ProductTemplate $productTemplate): BookingModel
    {
        $seats = 0;
        $totalPrice = 0;
        $bookedHeaders = [];
        $product = $this->getProduct($productTemplate->getProductId());

        foreach ($productTemplate->getTemplate()->getRows() as $row) {
            foreach ($row->getCols() as $col) {
                if($col->getBook() > 0){
                    $bookedHeaders[] = $row->getHeader();
                    $seats = $seats + $col->getBook();
                    $totalPrice = $totalPrice + (intval($product->get_price()) * $col->getBook());
                }
            }
        }
        $headerCounts = array_count_values($bookedHeaders);
        $maxCount = max($headerCounts);
        $topHeader = array_search($maxCount, $headerCounts);
        $uniqueBookedHeaders = array_unique($bookedHeaders);
        $uniqueBookedHeadersStr = implode(", ", $uniqueBookedHeaders);

        $bookingModel = new BookingModel();
        $bookingModel->setName($product->get_title());
        $bookingModel->setSeats($seats);
        $bookingModel->setProductId($productTemplate->getProductId());
        $bookingModel->setBookingDate($productTemplate->getKey());
        $bookingModel->setHeaders($uniqueBookedHeadersStr);
        $bookingModel->setTopHeader($topHeader);
        $bookingModel->setTotalPrice($totalPrice);
        $bookingModel->setTemplate($productTemplate->getTemplate());

        return $bookingModel;
    }

    public function getProduct($productId): BookingSlotProduct
    {
        return new BookingSlotProduct($productId);
    }
}