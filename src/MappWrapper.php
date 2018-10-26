<?php

/**
 * Mapp API PHP Wrapper
 *
 * PHP Version 5.5
 *
 * @category Library
 * @package  Mcarro\MappWrapper
 * @author   Marco Carrodano <marco.carrodano@gmail.com>
 * @license  https://github.com/zaccaro1980/mcarro-mapp/blob/master/LICENSE MIT
 * @link     https://github.com/zaccaro1980/mcarro-mapp
 */

namespace Mcarro;

use GuzzleHttp\Client;

/**
 * MappWrapper Class
 *
 * @category Class
 * @package  Mcarro\MappWrapper
 * @author   Marco Carrodano <marco.carrodano@gmail.com>
 * @license  https://github.com/zaccaro1980/mcarro-mapp/blob/master/LICENSE MIT
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
     * Class contructor
     *
     * @param [string] $apiUrl Api root Url
     * @param [string] $login Mapp Api Login
     * @param [string] $password Mapp Api Password
     *
     * @return void
     */
    public function __construct(string $apiUrl, string $login, string $password)
    {
        $this->client = new Client;
        $this->apiUrl = $apiUrl;
        $this->login = $login;
        $this->password = $password;
        $this->setMappDefaultParams();
    }

    /**
     * Function asyncSubmit
     *
     * @param string $topic The topic
     *
     * @return string Test
     */
    public function asyncSubmit(string $topic, $attributes = [])
    {
        $qs = [ 'topic' => $topic ];
        return $this->makeApiRequest('/async/submit', 'POST', $qs, $attributes);
    }

    public function userGetByEmail($email)
    {
        $qs = [ 'email' => $email ];
        return $this->makeApiRequest('/user/getByEmail', 'GET', $qs);
    }

    private function setMappDefaultParams()
    {
        $this->mappDefaultParams =  [
            'auth' => [
                $this->login,
                $this->password
            ],
            'json' => '',
            'query' => '',
            'verify' => false,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
            'http_errors' => true
        ];
    }

    private function makeApiRequest($endPoint, $method, $qs, $attributes = [])
    {
        $params = $this->mappDefaultParams;

        $params['json'] = $attributes;
        $params['query'] = $qs;

        return $this->client->request(
            $method,
            $this->apiUrl . $endPoint,
            $params
        );
    }
}
