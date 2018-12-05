<?php
/**
 * Created by PhpStorm.
 * User: trietl
 * Date: 12/3/18
 * Time: 5:48 PM
 */

namespace SpotInst;


interface SpotInstClientInterface
{
    /**
     * @param string $uri
     * @param array $jsonData
     * @return mixed
     */
    public function get(string $uri, array $jsonData);

    /**
     * @param string $uri
     * @param array $jsonData
     * @return mixed
     */
    public function post(string $uri, array $jsonData);

    /**
     * @param string $uri
     * @param array $jsonData
     * @return mixed
     */
    public function put(string $uri, array $jsonData);

    /**
     * @param string $uri
     * @param array $jsonData
     * @return mixed
     */
    public function delete(string $uri, array $jsonData);
}