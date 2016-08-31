<?php

/**
 * Interface UnifiedActivityCollection
 */
interface UnifiedActivityCollection {

    /**
     * Get unified record by activity's id.
     *
     * @param int $id
     * @return mixed
     */
    public function getUnifiedById($id=0);

    /**
     * @return string
     */
    public function getUnifiedType();

}

?>