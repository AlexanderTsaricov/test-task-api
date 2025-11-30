<?php

namespace App\Services;

class ApiClient
{
    private $key;

    public function __construct()
    {
        $this->key = env("API_KEY");
    }

    private function send(string $method, array $params)
    {
        $url = 'http://109.73.206.144:6969/api/' . $method;
        $params['key'] = $this->key;

        $url .= '?' . http_build_query($params);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);
        return json_decode($response, true);
    }

    public function getSales(string $dateFrom, string $dateTo, int $page = 1, int $limit = 100)
    {
        return $this->send('sales', compact('dateFrom', 'dateTo', 'page', 'limit'));
    }

    public function getOrders(string $dateFrom, string $dateTo, int $page = 1, int $limit = 100)
    {
        return $this->send('orders', compact('dateFrom', 'dateTo', 'page', 'limit'));
    }

    public function getStocks(int $page = 1, int $limit = 100)
    {
        $dateFrom = now()->format('Y-m-d');

        return $this->send('stocks', compact('dateFrom', 'page', 'limit'));
    }


    public function getIncomes(string $dateFrom, string $dateTo, int $page = 1, int $limit = 100)
    {
        return $this->send('incomes', compact('dateFrom', 'dateTo', 'page', 'limit'));
    }
}
