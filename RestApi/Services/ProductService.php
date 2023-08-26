<?php

namespace ONSBKS_Slots\RestApi\Services;

use ONSBKS_Slots\Includes\Models\Slot;
use ONSBKS_Slots\RestApi\Exceptions\NoSlotFoundException;
use ONSBKS_Slots\RestApi\Repositories\ProductRepository;

class ProductService
{
    private ProductRepository $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
    }


    /**
     * @param int $productId
     * @param string $key
     * @return Slot|null
     * @throws NoSlotFoundException
     */
    public function findProductTemplate(int $productId, string $key, bool $throwable = false): ?Slot
    {
        $slot = $this->productRepository->get_product_slot($productId, $key);

        if(!$slot && $throwable) throw new NoSlotFoundException();

        if(!$slot) return null;

        return $slot;
    }


}