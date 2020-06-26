<?php

namespace App\Http\Controllers;

use App\DebtorAccount;
use App\Debtor;
use App\DebtorType;
use Illuminate\Http\Request;

class DebtorAccountController extends Controller
{
    protected function getValidateRules()
    {
        return [
            'relations' => 'array',
            'relations.*.id' => 'alpha_dash',
            'relations.*.payment_uuid' => 'alpha_dash',
            'relations.*.service_uuid' => 'alpha_dash',
            'relations.*.amount' => 'numeric',
        ];
    }
    
    public function relations(DebtorAccount $account)
    {
        return view('home.debtors.communals.accounts.relations', compact('account'));
    }
    
    public function relationsStore(Request $request, DebtorAccount $account)
    {
        $data = $request->validate($this->getValidateRules());
        $account->payments_relation = $data['relations'];
        $account->save();
        
        return ['result' => true];
    }
    
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(DebtorAccount $debtorAccount)
    {
        //
    }

    public function edit(DebtorAccount $account)
    {
        return view('home.debtors.communals.accounts.edit', compact('account'));
    }

    public function update(Request $request, DebtorAccount $debtorAccount)
    {
        //
    }


    public function destroy(DebtorAccount $debtorAccount)
    {
        //
    }
}
