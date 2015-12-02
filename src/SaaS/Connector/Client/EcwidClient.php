<?php

/**
 * PHP version 5.3
 *
 * @package SaaS\Connector\Client
 * @author Alex Lushpai <lushpai@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT License
 * @link http://github.com/gwinn/saas-connector
 * @see http://api.ecwid.com/
 */
namespace SaaS\Connector\Client;

use SaaS\Connector\Http\Request\EcwidRequest;

/**
 * EcwidClient
 *
 * @package SaaS\Connector\Client
 * @author Alex Lushpai <lushpai@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT License
 * @link http://github.com/gwinn/saas-connector
 * @see http://api.ecwid.com/
 */
class EcwidClient
{

    private $storeId;
    private $client;

    /**
     * @param string $storeId
     * @param string $apiKey
     */
    public function __construct($storeId, $apiKey)
    {
        $this->storeId = $storeId;
        $this->client = new EcwidRequest(array('token' => $apiKey));
    }

    /**
     * Get product categories
     *
     * @param array $params
     *
     * @return Response
     */
    public function getCategories(array $params = array())
    {
        $url = $this->storeId . "/categories";

        return $this->client->makeRequest($url, EcwidRequest::METHOD_GET, $params);
    }

    /**
     * Get product category
     *
     * @param string $categoryId
     *
     * @return Response
     */
    public function getCategory($categoryId)
    {
        $url = $this->storeId . '/categories/' . $categoryId;
        return $this->client->makeRequest($url, EcwidRequest::METHOD_GET);
    }

    /**
     * Create product category
     *
     * @param array $params
     *
     * @return Response
     */
    public function createCategory(array $params = array())
    {
        $url = $this->storeId . '/categories';

        return $this->client->makeRequest($url, EcwidRequest::METHOD_POST, $params);
    }

    /**
     * Get products
     *
     * @param array $filter
     *
     * @return Response
     */
    public function getProducts(array $filter = array())
    {
        $url = $this->storeId . "/products";

        return $this->client->makeRequest($url, EcwidRequest::METHOD_GET, $filter);
    }

    /**
     * Get orders
     *
     * @param array $filter
     *
     * @return Response
     */
    public function getOrders(array $filter = array())
    {
        $url = $this->storeId . "/orders";

        return $this->client->makeRequest($url, EcwidRequest::METHOD_GET, $filter);
    }

    /**
     * Get order details
     *
     * @param string $orderId
     *
     * @return Response
     */
    public function getOrder($orderId)
    {
        $url = $this->storeId . "/orders/" . $orderId;

        return $this->client->makeRequest($url, EcwidRequest::METHOD_GET);
    }

    /**
     * Create order
     *
     * @param array $order
     *
     * @return Response
     */
    public function createOrder($order)
    {
        $url = $this->storeId . "/orders";
        $data = $this->normalizeFields(json_encode($order, JSON_UNESCAPED_UNICODE));
        $params = array('data' => $data);

        return $this->client->makeRequest($url, EcwidRequest::METHOD_POST, $params);
    }

    /**
     * Update order
     *
     * @param array $order
     *
     * @return Response
     */
    public function updateOrder($orderId, $order)
    {
        $url = $this->storeId . "/orders/" . $orderId;
        $data = $this->normalizeFields(json_encode($order, JSON_UNESCAPED_UNICODE));
        $params = array('data' => $data);

        return $this->client->makeRequest($url, EcwidRequest::METHOD_PUT, $params);
    }

    /**
     * Delete order
     *
     * @param array $orderId
     *
     * @return Response
     */
    public function deleteOrder($orderId)
    {
        $url = $this->storeId . "/orders/" . $orderId;

        return $this->client->makeRequest($url, EcwidRequest::METHOD_DELETE);
    }

    private function normalizeFields($json)
    {
         $json = preg_replace("/(subtotal|total\"\:)(\")([0-9.]*)(\")(,)/", "$1$3$5",  $json);
         $json = preg_replace("/(discount\"\:)(\")([0-9.]*)(\")(,)/", "$1$3$5",  $json);
         $json = preg_replace("/(shippingRate\"\:)(\")([0-9.]*)(\")(,)/", "$1$3$5",  $json);
         $json = preg_replace("/(price\"\:)(\")([0-9.]*)(\")(,)/", "$1$3$5",  $json);
         $json = preg_replace("/(quantity\"\:)(\")([0-9.]*)(\")(,)/", "$1$3$5",  $json);

         return $json;
    }
}
