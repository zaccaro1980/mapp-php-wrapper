<?php

/**
 * Mapp API PHP Wrapper.
 *
 * PHP Version 5.5
 *
 * @category Library
 *
 * @author   Marco Carrodano <marco.carrodano@gmail.com>
 * @license  https://github.com/zaccaro1980/mcarro-mapp/blob/master/LICENSE MIT
 *
 * @link     https://github.com/zaccaro1980/mcarro-mapp
 */

namespace Mcarro;

use GuzzleHttp\Client;

/**
 * MappWrapper Class.
 *
 * @category Class
 *
 * @author   Marco Carrodano <marco.carrodano@gmail.com>
 * @license  https://github.com/zaccaro1980/mcarro-mapp/blob/master/LICENSE MIT
 *
 * @link     https://github.com/zaccaro1980/mcarro-mapp
 */
class MappWrapper
{
    private $client;
    private $apiUrl;
    private $mappDefaultParams;
    private $login;
    private $password;

    /**
     * Class contructor.
     *
     * @param [string] $apiUrl   Api root Url
     * @param [string] $login    Mapp Api Login
     * @param [string] $password Mapp Api Password
     *
     * @return void
     */
    public function __construct($apiUrl, $login, $password)
    {
        $this->client = new Client();
        $this->apiUrl = $apiUrl;
        $this->login = $login;
        $this->password = $password;
        $this->setMappDefaultParams();
    }

    public function __call($name, $attributes)
    {
        return $this->makeApiRequest(
            $this->apiUrl . '/' . $this->translateMethodNameToMappEndpoint($name),
            ($attributes[0]['method']) ?? 'GET',
            ($attributes[0]['query']) ?? '',
            ($attributes[0]['body']) ?? ''
        );
    }

    private function setMappDefaultParams()
    {
        $this->mappDefaultParams = [
            'auth' => [
                $this->login,
                $this->password,
            ],
            'json'    => '',
            'query'   => '',
            'verify'  => false,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ],
            'http_errors' => true,
        ];
    }

    private function makeApiRequest($endPoint, $method, $query = [], $body = [])
    {
        $params = $this->mappDefaultParams;

        $params['json'] = $body;
        $params['query'] = $query;

        return $this->client->request(
            $method,
            $endPoint,
            $params
        );
    }

    private function translateMethodNameToMappEndpoint($methodName)
    {
        return preg_replace_callback('/([A-Z])/', function ($word) {
            return '/' . strtolower($word[1]);
        }, $methodName, 1);
    }
}
