<?php


/**
 * Interface UnifiedDiscount
 */
interface UnifiedDiscount {

    /**
     * Check the spec can use the discount
     * $spec array Format: {id:<int>, product_id:<int>, activity_id:<int>, amount:<int>}
     * return bool
     */
    public function canUsedToSpec($spec=array());

    /**
     * Get price for the spec that use the discount.
     * $spec array Format: {id:<int>, product_id:<int>, activity_id:<int>, amount:<int>}
     * return float
     */
    public function getPriceForSpec($spec=array());
}

?>