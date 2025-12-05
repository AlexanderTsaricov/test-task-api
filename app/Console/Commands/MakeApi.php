<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Api;

class MakeApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:api 
        {name : Название API сервиса} 
        {base_url : Базовый URL} 
        {--get= : Endpoint GET} 
        {--put= : Endpoint PUT} 
        {--delete= : Endpoint DELETE}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание апи';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $base_url = $this->argument('base_url');

        // Необязательные опции
        $get_method = $this->option('get');
        $put_method = $this->option('put');
        $delete_method = $this->option('delete');

        try {
            $searchApi = Api::where('name', $name)->firstOrFail();
            $this->info("API с таким NAME уже существует.");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            $api = new Api([
                'name' => $name,
                'base_url' => $base_url,
                'get' => $get_method,
                'put' => $put_method,
                'delete' => $delete_method
            ]);

            $api->save();
            $this->info("Создан апи " . $name);
        }
    }
}
