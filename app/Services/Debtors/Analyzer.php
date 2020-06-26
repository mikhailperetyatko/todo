<?php

namespace App\Services\Debtors;

use App\DebtorAccount;
use App\Debtor;
use Carbon\Carbon;

class Analyzer
{
    protected $account;
    
    public function __construct(DebtorAccount $account)
    {
        $this->account = $account;
        dd($this->account->balance);
    }
    
    
    
}