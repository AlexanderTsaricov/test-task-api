<?php

use App\Models\Account;
use App\Models\Company;
use App\Models\Token;
use App\Models\TokenType;
use Illuminate\Support\Facades\Artisan;

$accounts = Account::all();

foreach ($accounts as $account) {
    $token = Token::where('account_id', $account->id)->first();
    if (!$token) {
        continue;
    }

    $tokenType = TokenType::find((int)$token->token_type_id);
    $company   = Company::find((int)$account->company_id);

    if (!$company || !$tokenType) {
        continue;
    }

    // просто выполняем команду
    Artisan::call("import:data {$company->id} {$account->id} {$tokenType->id} --onlyActual");

    echo "Выполнено для company={$company->id}, account={$account->id}, tokenType={$tokenType->id}\n";
}
