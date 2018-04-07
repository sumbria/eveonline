<?php

namespace Sumbria\EveOnline;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Class EveOnline
 *
 * @package Sumbria
 * 
 * @author Balbinder Sumbria
 */
class EveOnline {
    /*
     * @var string $oauth_url is the base url for api call
     */

    public static $oauth_url = 'https://login.eveonline.com/oauth/';
    /*
     * @var string The EveOnline response type used for api requests.
     */
    public static $responseType = 'code';
    /*
     * @var string The redirect url used for api request callback. After authentication the user will be redirected to this URL on your website.
     */
    public static $redirectUri;
    /*
     * @var string The EveOnline client id to be used for api requests. A string identifier for the client, provided by CCP.
     */
    public static $clienId;
    /*
     * @var array Guzzle client handle for api calls.
     */
    public static $client;
    /*
     * @var array The EveOnline scopes to be used for api requests. The requested scopes as a space delimited string.
     */
    public static $scope = [];
    /*
     * @var string An opaque value used by the client to maintain state between the request and callback. The SSO includes this value when redirecting back to the 3rd party website. While not required, it is important to use this for security reasons. http://www.thread-safe.com/2014/05/the-correct-use-of-state-parameter-in.html explains why the state parameter is needed.
     */
    public static $state;
    public static $accessToken;

    public function __construct() {
        self::$client = new Client([
            'verify' => false
        ]);
    }

    /*
      @return string Sets EveOnline client id to be used for api requests. A string identifier for the client, provided by CCP.
     */

    public static function setClientId($client_id) {
        self::$clienId = $client_id;
    }

    /*
     * @return string The EveOnline client id to be used for api requests. A string identifier for the client, provided by CCP.
     */

    public static function getClientId() {
        return self::$clienId;
    }

    /*
     * @return string Sets redirect url used for api request callback. After authentication the user will be redirected to this URL on your website.
     */

    public static function setRedirectUri($redirect_uri) {
        self::$redirectUri = urlencode($redirect_uri);
    }

    /*
     * @return array Sets scopes to be used for api requests. The requested scopes as a space delimited string.
     */

    public static function setScope($scopes = []) {
        if (is_array($scopes) && count($scopes) > 0) {
            $scopes_string = '';
            foreach ($scopes as $scope) {
                $scopes_string .= $scope . ' ';
            }
            self::$scope = urlencode($scopes_string);
        } else {
            return [
                "status" => 401,
                "data" => 'Scopes parameter missing.'
            ];
        }
    }

    /*
     * @return string The EveOnline scopes to be used for api requests. The requested scopes as a space delimited string.
     */

    public static function getScope() {
        return self::$scope;
    }

    /*
     * @var string Sets opaque value used by the client to maintain state between the request and callback. The SSO includes this value when redirecting back to the 3rd party website. While not required, it is important to use this for security reasons. http://www.thread-safe.com/2014/05/the-correct-use-of-state-parameter-in.html explains why the state parameter is needed.
     */

    public static function setState($state) {
        self::$state = $state;
    }

    /*
     * @var string An opaque value used by the client to maintain state between the request and callback. The SSO includes this value when redirecting back to the 3rd party website. While not required, it is important to use this for security reasons. http://www.thread-safe.com/2014/05/the-correct-use-of-state-parameter-in.html explains why the state parameter is needed.
     */

    public static function getState() {
        return self::$state;
    }

    /*
     * @var string The redirect url used for api request callback. After authentication the user will be redirected to this URL on your website.
     */

    public static function getRedirectUri() {
        return self::$redirectUri;
    }

    public static function setAccessToken($accessToken) {
        self::$accessToken = $accessToken;
    }

    public static function getAccessToken() {
        return self::$accessToken;
    }

    /*
     * @return string Return auth url for api authorization
     */

    public function getAuthUrl() {
        $auth_url = self::$oauth_url . 'authorize/?response_type=' . self::$responseType . '&redirect_uri=' . self::getRedirectUri() . '&client_id=' . self::getClientId() . '&scope=' . self::getScope();
        if (isset(self::getState()) && self::getState()) {
            $auth_url .= '&state=' . self::getState();
        }
        return $auth_url;
    }

    protected function call($url, $method = 'get', $post = [], $is_header = true) {
        try {
            if ($is_header) {
                $accessToken = self::getAccessToken();
                if (!$accessToken) {
                    return [
                        "status" => 401,
                        "data" => 'Access Token Missing.'
                    ];
                }
                $headers = ['Authorization' => 'Bearer ' . $accessToken];
            } else {
                $headers = [];
            }
            $response = $this->client->request($method, self::$base_url . $url, ['query' => $post, 'headers' => $headers]);
            return $this->success($response);
        } catch (RequestException $e) {
            return $this->error($e);
        }
    }

    protected function success($response) {
        return [
            "status" => $response->getStatusCode(),
            "data" => json_decode($response->getBody()->getContents())
        ];
    }

    protected function error($e) {
        return [
            "status" => $e->getCode(),
            "error" => json_decode($e->getResponse()->getBody(true)->getContents())
        ];
    }

}
