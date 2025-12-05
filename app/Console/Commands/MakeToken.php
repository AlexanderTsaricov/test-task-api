<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Token;
use App\Models\Account;
use App\Models\Api;
use App\Models\TokenType;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MakeToken extends Command
{
    protected $signature = 'make:token 
        {account_id : ID аккаунта} 
        {api_id : ID API сервиса} 
        {token_type_id : ID типа токена} 
        {token : токен} 
        {--expires_at= : Дата истечения токена (YYYY-MM-DD HH:MM:SS)}';

    protected $description = 'Создание токена';

    public function handle()
    {
        $accountId   = (int)$this->argument('account_id');
        $apiId       = (int)$this->argument('api_id');
        $tokenTypeId = (int)$this->argument('token_type_id');
        $tokenValue  = $this->argument('token');

        // Необязательная опция
        $expiresAt   = $this->option('expires_at');

        // Проверка существования связанных сущностей
        try {
            Account::findOrFail($accountId);
            Api::findOrFail($apiId);
            TokenType::findOrFail($tokenTypeId);
        } catch (ModelNotFoundException $e) {
            $this->info("Указанный account_id, api_id или token_type_id не существует.");
            return;
        }

        // Проверка на существование токена с таким значением
        if (Token::where('token', $tokenValue)->exists()) {
            $this->info("Токен с таким значением уже существует.");
            return;
        }

        // Создание токена
        $token = new Token([
            'account_id'    => $accountId,
            'api_id'        => $apiId,
            'token_type_id' => $tokenTypeId,
            'token'         => $tokenValue,
            'expires_at'    => $expiresAt,
        ]);

        $token->save();

        $this->info("Создан токен '{$tokenValue}' для account_id={$accountId}, api_id={$apiId}, type_id={$tokenTypeId}");
    }
}
