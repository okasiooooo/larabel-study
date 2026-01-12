<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // トランザクション制御に必要

class TransactionController extends Controller
{
    /**
     * 登録画面を表示する (Access: Form_Load)
     */
    public function create()
    {
        // 勘定科目マスタを全件取得して、画面に渡す
        // これがないと、ドロップダウンリストの中身が空っぽになります
        $accounts = Account::all();

        // views/transactions/create.blade.php を表示
        // compact('accounts') で、変数を画面側に渡しています
        return view('transactions.create', compact('accounts'));
    }

    /**
     * データを保存する (Access: 保存ボタンのOnClick処理)
     */
    public function store(Request $request)
    {
        // 1. バリデーション (入力チェック)
        // ここで不正なデータは弾きます
        $validated = $request->validate([
            'transaction_date' => 'required|date',     // 日付必須
            'description'      => 'nullable|string',   // 摘要は任意
            'entries'          => 'required|array|min:2', // 明細は配列で、最低2行必要
            
            // 配列の中身（明細行）ごとのチェック
            'entries.*.account_id' => 'required|exists:accounts,id', // 科目はマスタに存在すること
            'entries.*.amount'     => 'required|integer|min:1',      // 金額は1以上の整数
            'entries.*.type'       => 'required|in:debit,credit',    // 貸借区分
        ]);

        // ★重要: DBトランザクション開始
        // 失敗したら全ロールバック（なかったことにする）機能
        DB::transaction(function () use ($validated) {
            
            // 2. 親テーブル(取引ヘッダー)の保存
            $transaction = Transaction::create([
                'transaction_date' => $validated['transaction_date'],
                'description'      => $validated['description'],
            ]);

            // 3. 子テーブル(仕訳明細)の保存
            // フォームから送られてきた明細行(entries)をループして保存
            foreach ($validated['entries'] as $entry) {
                // $transaction->journalEntries() と書くことで
                // transaction_id は自動で入ります
                $transaction->journalEntries()->create([
                    'account_id' => $entry['account_id'],
                    'amount'     => $entry['amount'],
                    'entry_type' => $entry['type'],
                ]);
            }
        });

        // 4. 保存完了後の処理
        // 完了メッセージ付きで、元の画面に戻る
        return redirect()->route('transactions.create')
            ->with('success', '仕訳を登録しました！');
    }
}