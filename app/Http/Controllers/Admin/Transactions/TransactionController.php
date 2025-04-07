<?php

namespace App\Http\Controllers\Admin\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function listesTransactions()
    {
        return view('transactions.listes_transactions');
    }
    public function transactionLitiges()
    {
        return view('transactions.transaction_litiges');
    }
}
