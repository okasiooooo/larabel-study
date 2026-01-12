<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

// トップページにアクセスしたら、取引登録画面へ転送
Route::get('/', function () {
    return redirect()->route('transactions.create');
});

// 取引関連のルートを一括定義 (RESTfulルーティング)
// これ1行で、一覧(index)、作成(create)、保存(store)などのURLが自動生成されます
Route::resource('transactions', TransactionController::class);