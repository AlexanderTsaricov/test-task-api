<?php

use Illuminate\Support\Facades\Schedule;
use App\Models\Account;
use App\Models\Company;
use App\Models\Token;
use App\Models\TokenType;

Schedule::call(function () {
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

        Schedule::command("import:data {$company->id} {$account->id} {$tokenType->id}")
            ->twiceDaily(3, 12)
            ->appendOutputTo(storage_path('logs/import.log'));
    }
});
