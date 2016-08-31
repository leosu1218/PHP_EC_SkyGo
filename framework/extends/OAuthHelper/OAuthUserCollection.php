<?php

/**
 * Interface OAuthUserCollection
 */
interface OAuthUserCollection {
    /**
     * Get user's record by provider open id.
     * @param string $providerType
     * @param string $providerId
     * @return array
     */
    public function getRecordByProviderId($providerType='', $providerId='', $attributes=array());

    /**
     * Create new user record by provider id.
     * @param string $name
     * @param string $account
     * @param string $providerType
     * @param string $providerId
     * @param array $attributes
     * @return int|void
     * @throws AuthorizationException
     * @throws DbOperationException
     */
    public function createRecordByProviderId($name='', $account='', $providerType='', $providerId='', $attributes=array());
}

?>