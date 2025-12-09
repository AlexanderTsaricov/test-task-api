<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Company;
use Illuminate\Console\Command;
use App\Services\ApiClient;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Income;
use App\Models\Sale;
use App\Models\TokenType;
use Carbon\Carbon;

class ImportApiData extends Command
{
    protected $signature = 'import:data {company} {account} {tokenType} {--onlyActual}';
    protected $description = 'Импорт данных из API (company, account, tokenType)';

    /**
     * Импорт данных из API (orders, stocks, incomes, sales)
     */
    public function handle()
    {
        $company = Company::find((int)$this->argument('company'));
        $account = Account::find((int)$this->argument('account'));
        $tokenType = TokenType::find((int)$this->argument('tokenType'));

        $onlyActual = $this->option('onlyActual');
        $limit = 100;


        if ($company == null) {
            $this->error('Не найдена компания с таким id');
            return 1;
        }

        if ($account == null) {
            $this->error('Не найден аккаунт с таким id');
            return 1;
        }

        if ($tokenType == null) {
            $this->error('Не найден тип токена с таким id');
            return 1;
        }


        try {
            $client = new ApiClient($company, $account, $tokenType);
        } catch (\InvalidArgumentException $e) {
            $this->error($e->getMessage());
            return 1;
        }
        $types = ['sales', 'orders', 'stocks', 'incomes'];
        for ($i = 0; $i < count($types); $i++) {
            $type = $types[$i];
            $page = 1;
            $dateFrom = null;
            $last = null;
            switch ($type) {
                case 'sales':
                    $last = Sale::where('account_id', $account->id)
                        ->orderBy('created_at', 'desc')
                        ->first();
                    break;

                case 'orders':
                    $last = Order::where('account_id', $account->id)
                        ->orderBy('created_at', 'desc')
                        ->first();
                    break;

                case 'stocks':
                    $last = Stock::where('account_id', $account->id)
                        ->orderBy('created_at', 'desc')
                        ->first();
                    break;

                case 'incomes':
                    $last = Income::where('account_id', $account->id)
                        ->orderBy('created_at', 'desc')
                        ->first();
                    break;
                default:
                    $this->error("Неизвестный тип: {$type}");
                    return;
            }

            $dateTo = $type === 'incomes'
                ? Carbon::now()->format('Y-m-d H:i:s')
                : Carbon::now()->format('Y-m-d');

            if (!$onlyActual) {
                $dateFrom = Carbon::today()->startOfDay()->format('Y-m-d H:i:s');
                $dateTo = Carbon::now()->format('Y-m-d H:i:s');
                if ($last) {
                    if ($type === 'incomes') {
                        // Для incomes — следующая секунда после последней записи
                        $dateFrom = Carbon::parse($last->created_at)
                            ->addSecond()
                            ->format('Y-m-d H:i:s');
                    } else {
                        // Для остальных — начало следующего дня (00:00:01)
                        $dateFrom = Carbon::parse($last->created_at)
                            ->addDay()
                            ->startOfDay()
                            ->addSecond()
                            ->format('Y-m-d H:i:s');
                    }
                } else {
                    // Если записей нет — берём месяц назад
                    $dateFrom = Carbon::now()
                        ->subMonth()
                        ->format('Y-m-d H:i:s');
                }
            } else {
                $dateFrom = Carbon::today()->startOfDay()->format('Y-m-d H:i:s');
                $dateTo   = Carbon::now()->format('Y-m-d H:i:s');
            }

            $this->info("Будут импортированы данные от: " . $dateFrom . " до: " . $dateTo);

            do {
                switch ($type) {
                    case 'sales':
                        // sales только дату (Y-m-d)
                        $data = $client->getSales(
                            date('Y-m-d', strtotime($dateFrom)),
                            date('Y-m-d', strtotime($dateTo)),
                            $page,
                            $limit
                        );
                        break;

                    case 'orders':
                        // orders только дату
                        $data = $client->getOrders(
                            date('Y-m-d', strtotime($dateFrom)),
                            date('Y-m-d', strtotime($dateTo)),
                            $page,
                            $limit
                        );
                        break;

                    case 'stocks':
                        if (!$last || $last->created_at->format('Y-m-d') !== date('Y-m-d')) {
                            $data = $client->getStocks($page, $limit);
                        } else {
                            $this->info('Сегодня stocks уже были запрошены');
                        }
                        break;

                    case 'incomes':
                        $data = $client->getIncomes(
                            date('Y-m-d', strtotime($dateFrom)),
                            date('Y-m-d', strtotime($dateTo)),
                            $page,
                            $limit
                        );
                        break;

                    default:
                        $this->error("Неизвестный тип: {$type}");
                        return;
                }

                foreach ($data['data'] ?? [] as $item) {
                    switch ($type) {
                        case 'orders':
                            Order::updateOrCreate(
                                [
                                    'account_id' => $account->id,
                                    'g_number'   => $item['g_number'],
                                ],
                                array_merge($item, ['account_id' => $account->id])
                            );
                            break;

                        case 'stocks':
                            Stock::updateOrCreate(
                                [
                                    'account_id' => $account->id,
                                    'date'       => $item['date'],
                                ],
                                array_merge($item, ['account_id' => $account->id])
                            );


                            break;

                        case 'incomes':
                            Income::updateOrCreate(
                                [
                                    'account_id' => $account->id,
                                    'income_id'  => $item['income_id'],
                                    'nm_id'      => $item['nm_id'],
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
                                    'account_id'       => $account->id,
                                ]
                            );
                            break;

                        case 'sales':
                            Sale::updateOrCreate(
                                [
                                    'account_id' => $account->id,
                                    'sale_id'    => $item['sale_id'],
                                ],
                                array_merge($item, ['account_id' => $account->id])
                            );
                            break;
                    }
                }


                $this->info("Импортировано " . count($data['data'] ?? []) . " записей со страницы {$page} типа {$type}");
                $page++;
            } while (!empty($data['data']));
        }
    }
}
