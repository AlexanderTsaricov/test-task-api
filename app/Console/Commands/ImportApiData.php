<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ApiClient;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Income;
use App\Models\Sale;

class ImportApiData extends Command
{
    protected $signature = 'import:data {type} {dateFrom?} {dateTo?} {limit?}';
    protected $description = 'Импорт данных из API (orders, stocks, incomes, sales)';

    /**
     * Импорт данных из API (orders, stocks, incomes, sales)
     */
    public function handle(ApiClient $client)
    {
        $type = $this->argument('type');
        $dateFrom = $this->argument('dateFrom') ?? now()->subDay()->format('Y-m-d');
        $dateTo   = $this->argument('dateTo') ?? now()->format('Y-m-d');
        $limit   = $this->argument('limit') ?? 100;

        $page = 1;
        do {
            switch ($type) {
                case 'sales':
                    $data = $client->getSales($dateFrom, $dateTo, $page, $limit);
                    break;
                case 'orders':
                    $data = $client->getOrders($dateFrom, $dateTo, $page, $limit);
                    break;
                case 'stocks':
                    $data = $client->getStocks($page, $limit); 
                    break;
                case 'incomes':
                    $data = $client->getIncomes($dateFrom, $dateTo, $page, $limit);
                    break;
                default:
                    $this->error("Неизвестный тип: {$type}");
                    return;
            }

            foreach ($data['data'] ?? [] as $item) {
                switch ($type) {
                    case 'orders':
                        \App\Models\Order::updateOrCreate(
                            ['g_number' => $item['g_number']],
                            $item
                        );
                        break;

                    case 'stocks':
                        \App\Models\Stock::updateOrCreate(
                            ['barcode' => $item['barcode'], 'date' => $item['date']],
                            $item
                        );
                        break;

                    case 'incomes':
                        Income::updateOrCreate(
                            [
                                'income_id' => $item['income_id'],
                                'nm_id'     => $item['nm_id'],
                            ],
                            [
                                'number'           => $item['number'],
                                'date'             => $item['date'],
                                'last_change_date' => $item['last_change_date'],
                                'supplier_article' => $item['supplier_article'],
                                'tech_size'        => $item['tech_size'],
                                'barcode'          => $item['barcode'],
                                'quantity'         => $item['quantity'],
                                'total_price'      => $item['total_price'],
                                'date_close'       => $item['date_close'],
                                'warehouse_name'   => $item['warehouse_name'],
                            ]
                        );

                        break;

                    case 'sales':
                        \App\Models\Sale::updateOrCreate(
                            ['g_number' => $item['g_number']],
                            $item
                        );
                        break;
                }
            }

            $this->info("Импортировано " . count($data['data'] ?? []) . " записей со страницы {$page}");
            $page++;
        } while (!empty($data['data']));
    }
}
