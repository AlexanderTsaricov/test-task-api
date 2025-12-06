<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Api;
use App\Models\Company;
use App\Models\Token;
use App\Models\TokenType;
use InvalidArgumentException;
use RuntimeException;

class ApiClient
{
    private ?Token $token;
    private ?Api $api;
    private string $key;

    public function __construct(Company $company, Account $account, TokenType $tokenType)
    {
        if ($account->company_id != $company->id) {
            throw new InvalidArgumentException('Аккаунт не пренадлежит компании');
        }

        $this->token = Token::where('account_id', (int)$account->id)->where('token_type_id', (int)$tokenType->id)->first();

        if ($this->token == null) {
            throw new InvalidArgumentException('Не найден токен аккаунта');
        }

        $this->key = $this->token->token;


        $this->api = Api::find((int)$this->token->api_id);

        if ($this->api == null) {
            throw new InvalidArgumentException('Не апи токена');
        }
    }

    private function sendRequest(string $url, int $retry = 0)
    {
        $ch = curl_init($url);

        if ($ch === false) {
            throw new RuntimeException('Не удалось инициализировать cURL');
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($responseCode == 429) {
            sleep(10);
            if ($retry >= 5) {
                throw new RuntimeException("API вернул 429 Too Many Requests слишком много раз");
            }
            return $this->sendRequest($url, $retry + 1);
        } else {

            file_put_contents(__DIR__ . "/log.log", print_r($response, true));

            if (curl_errno($ch)) {
                throw new RuntimeException('Curl error: ' . curl_error($ch));
            }

            curl_close($ch);
            return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        }
    }

    private function get(string $method, array $params)
    {
        if ($this->api != null) {
            $url = $this->api->base_url . $this->api->get . "$method";
            $params['key'] = $this->key;

            $url .= '?' . http_build_query($params);

            return $this->sendRequest($url);
        }
    }

    private function put(string $method, array $params)
    {
        if ($this->api != null) {
            $url = $this->api->base_url . $this->api->put . "$method";
            $params['key'] = $this->key;

            $url .= '?' . http_build_query($params);

            return $this->sendRequest($url);
        }
    }

    private function delete(string $method, array $params)
    {
        if ($this->api != null) {
            $url = $this->api->base_url . $this->api->delete . "$method";
            $params['key'] = $this->key;

            $url .= '?' . http_build_query($params);

            return $this->sendRequest($url);
        }
    }

    /**
     * Получение значений sales из апи
     *
     * @param string $dateFrom - дата от какого числа нужно получить данные
     * @param string $dateTo - дата по какое число нужно получить данные
     * @param integer $page - запрашиваемая страница выборки данных
     * @param integer $limit - лимит страницы
     * @return void
     */
    public function getSales(string $dateFrom, string $dateTo, int $page = 1, int $limit = 100)
    {
        return $this->get('sales', compact('dateFrom', 'dateTo', 'page', 'limit'));
    }

    /**
     * Получение значений orders из апи
     *
     * @param string $dateFrom - дата от какого числа нужно получить данные
     * @param string $dateTo - дата по какое число нужно получить данные
     * @param integer $page - запрашиваемая страница выборки данных
     * @param integer $limit - лимит страницы
     * @return void
     */
    public function getOrders(string $dateFrom, string $dateTo, int $page = 1, int $limit = 100)
    {
        return $this->get('orders', compact('dateFrom', 'dateTo', 'page', 'limit'));
    }

    /**
     * Получение значений stocks из апи
     *
     * @param string $dateFrom - дата от какого числа нужно получить данные
     * @param string $dateTo - дата по какое число нужно получить данные
     * @param integer $page - запрашиваемая страница выборки данных
     * @param integer $limit - лимит страницы
     * @return void
     */
    public function getStocks(int $page = 1, int $limit = 100)
    {
        $dateFrom = now()->format('Y-m-d');

        return $this->get('stocks', compact('dateFrom', 'page', 'limit'));
    }


    /**
     * Получение значений incomes из апи
     *
     * @param string $dateFrom - дата от какого числа нужно получить данные
     * @param string $dateTo - дата по какое число нужно получить данные
     * @param integer $page - запрашиваемая страница выборки данных
     * @param integer $limit - лимит страницы
     * @return void
     */
    public function getIncomes(string $dateFrom, string $dateTo, int $page = 1, int $limit = 100)
    {
        return $this->get('incomes', compact('dateFrom', 'dateTo', 'page', 'limit'));
    }
}
