<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TokenType;

class MakeTokenType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:token-type {name : имя токена}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание типа токена';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        try {
            $searchType = TokenType::where('name', $name)->firstOrFail();
            $this->info("Тип токенов с таким NAME уже существует.");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $type = new TokenType([
                'name' => $name
            ]);

            $type->save();
            
            $this->info("Создан тип токенов " . $name);
        }
    }
}
