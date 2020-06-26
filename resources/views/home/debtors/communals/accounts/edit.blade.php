@extends('layout.without_sidebar')

@section('content')
    <div class="container">
        <debtor-account :account='@json($account)' :form='@json(['action' => route('debtors.communals.accounts.update', ['account' => $account->id])])'>Подождите...</debtor-account>
    </div>
@endsection
