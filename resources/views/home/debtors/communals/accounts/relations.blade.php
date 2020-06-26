@extends('layout.without_sidebar')

@section('content')
    <div class="container">
        <debtor-relations :account='@json($account)' :form='@json(['action' => route('debtors.communals.accounts.relationsStore', ['account' => $account->id])])'>Подождите...</debtor-relations>
    </div>
@endsection
