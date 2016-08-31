<?php


/**
 * Interface UnifiedFare
 */
interface UnifiedFare {

    /**
     * Compute fare with the cart.
     * @return float
     */
    public function getUnifiedFare();

    /**
     * Get fare type
     * @return mixed
     * @throws Exception
     */
    public function getUnifiedType();

    /**
     * Get fare id
     * @return mixed
     */
    public function getUnifiedId();

    /**
     * Notify when self appended to cart.
     * @param UnifiedCartCollection $cart
     * @return mixed
     */
    public function onAppendToCart(UnifiedCartCollection $cart);

    /**
     * Check the fare can be used for the product.
     * @param int $id
     * @return bool
     */
    public function isUsedForProduct($id=0);
}

?>