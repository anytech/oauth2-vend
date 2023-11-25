<?php

namespace Anytech\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class Vend extends AbstractProvider
{
    /**
     * @var string
     */
    public $domainPrefix;

    public $authorizationHeader = 'Bearer';

    public function __construct($options = [])
    {
        if (empty($options['domainPrefix'])) {
            throw new \RuntimeException('Vend provider requires a "domainPrefix" option');
        }
        parent::__construct($options);
    }

    public function getBaseAuthorizationUrl() {
        // Implement this method
    }

    public function getBaseAccessTokenUrl(array $params) {
        // Implement this method
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token) {
        // Implement this method
    }

    // Implement other required methods

    protected function getDefaultScopes() {
        // Implement this method if needed
    }

    protected function checkResponse(PsrResponseInterface $response, $data) {
        // Implement this method if needed
    }

    protected function createResourceOwner(array $response, AccessToken $token) {
        // Implement this method if needed
    }

    /**
     * Get a Vend API URL, depending on path.
     *
     * @param  string $path
     * @return string
     */
    protected function getApiUrl($path)
    {
        return sprintf(
            'https://%s.vendhq.com/api/%s',
            $this->domainPrefix,
            $path
        );
    }

    public function urlAuthorize()
    {
        return 'https://secure.vendhq.com/connect';
    }

    public function urlAccessToken()
    {
        return $this->getApiUrl('1.0/token');
    }

    public function urlUserDetails(AccessToken $token)
    {
        throw new \RuntimeException('Vend does not provide details for single users');
    }

    public function userDetails($response, AccessToken $token)
    {
        return [];
    }

    /**
     * Helper method that can be used to fetch API responses.
     *
     * @param  string      $path
     * @param  AccessToken $token
     * @param  boolean     $as_array
     * @return array|object
     */
    public function getApiResponse($path, AccessToken $token, $as_array = true)
    {
        $url = $this->getApiUrl($path);

        $headers = $this->getHeaders($token);

        return json_decode($this->fetchProviderData($url, $headers), $as_array);
    }
}
