<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\Account;

class MakeAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:account {name} {company_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание аккаунта компании';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $company_id = (int)$this->argument('company_id');
        try {
            $company = Company::findOrFail($company_id);
            $account = new Account(['name' => $name, 'company_id' => $company_id]);

            $account->save();
            $this->info("Создан аккаунт ".$name);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->info("Компания с таким ID не найдена.");
        }
    }
}
