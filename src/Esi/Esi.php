<?php

namespace Sumbria\Esi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Class Esi
 *
 * @package Sumbria
 * 
 * @author Balbinder Sumbria
 */
class Esi {
    /*
     * @var string $base_url is the base url for api call
     */

    public static $base_url = 'https://esi.tech.ccp.is/latest/';
    public static $accessToken;
    public static $dataSource = 'tranquility';

    public function __construct() {
        $this->client = new Client([
            'verify' => false
        ]);
    }

    public function setDataSource($source) {
        self::$dataSource = $source;
    }

    public function getDataSource() {
        return self::$dataSource;
    }

    public function setAccessToken($token) {
        self::$accessToken = $token;
    }

    public function getAccessToken() {
        return self::$accessToken;
    }

    public function call($url, $method = 'get', $post = [], $is_header = true, $page = 0) {
        try {
            if ($is_header) {
                $accessToken = $this->getAccessToken();
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
            $endpoint = self::$base_url . $url . '/?datasource=' . $this->getDataSource();
            if ($page) {
                $endpoint = self::$base_url . $url . '/?datasource=' . $this->getDataSource() . '&page=' . $page;
            }
            $response = $this->client->request($method, $endpoint, ['query' => $post, 'headers' => $headers]);
            return $this->success($response);
        } catch (RequestException $e) {
            return $this->error($e);
        }
    }

    public function callUrl($url, $method = 'get', $page = 0) {
        $endpoint = self::$base_url . $url . '/?datasource=' . $this->getDataSource();
        if ($page) {
            $endpoint = self::$base_url . $url . '/?datasource=' . $this->getDataSource() . '&page=' . $page;
        }
        try {
            $response = $this->client->request($method, $endpoint);
            return $this->success($response);
        } catch (RequestException $e) {
            return $this->error($e);
        }
    }

    protected function success($response) {
        return [
            "status" => $response->getStatusCode(),
            "headers" => $response->getHeaders(),
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
