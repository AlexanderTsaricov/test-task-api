<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;

class MakeCompany extends Command
{
    /**
     * 
     *
     * @var string
     */
    protected $signature = 'make:company 
        {name : Название компании} 
        {email : Email компании} 
        {phone : Телефон компании} 
        {--address= : Адрес} 
        {--city= : Город} 
        {--country= : Страна} 
        {--website= : Сайт} 
        {--industry= : Отрасль} 
        {--tax_id= : ИНН}';

    /*
     * 
     *
     * @var string
     */
    protected $description = 'Создание компании';


    public function handle()
    {
        // обязательные аргументы
        $name  = $this->argument('name');
        $email = $this->argument('email');
        $phone = $this->argument('phone');

        // необязательные опции
        $address  = $this->option('address');
        $city     = $this->option('city');
        $country  = $this->option('country');
        $website  = $this->option('website');
        $industry = $this->option('industry');
        $taxId    = $this->option('tax_id');

        $company = new \App\Models\Company([
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone,
            'address'  => $address,
            'city'     => $city,
            'country'  => $country,
            'website'  => $website,
            'industry' => $industry,
            'tax_id'   => $taxId,
        ]);

        $company->save();

        $this->info("Компания '{$company->name}' успешно добавлена.");
    }
}
