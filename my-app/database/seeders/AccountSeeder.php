<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account; // Accountモデルを使う宣言

class AccountSeeder extends Seeder
{
    public function run()
    {
        // データの定義（配列の配列）
        $accounts = [
            // 資産 (Asset)
            ['code' => '1001', 'name' => '現金', 'type' => 'asset'],
            ['code' => '1002', 'name' => '普通預金', 'type' => 'asset'],
            
            // 負債 (Liability)
            ['code' => '2001', 'name' => 'クレカ未払金', 'type' => 'liability'],
            
            // 純資産 (Equity)
            ['code' => '3001', 'name' => '元入金', 'type' => 'equity'],
            
            // 収益 (Revenue)
            ['code' => '4001', 'name' => '給料受取', 'type' => 'revenue'],
            ['code' => '4002', 'name' => '雑収入', 'type' => 'revenue'],
            
            // 費用 (Expense)
            ['code' => '5001', 'name' => '食費', 'type' => 'expense'],
            ['code' => '5002', 'name' => '日用品費', 'type' => 'expense'],
            ['code' => '5003', 'name' => '交通費', 'type' => 'expense'],
            ['code' => '5004', 'name' => '交際費', 'type' => 'expense'],
            ['code' => '5005', 'name' => '水道光熱費', 'type' => 'expense'],
        ];

        // ループして1件ずつ保存
        foreach ($accounts as $account) {
            Account::create($account);
        }
    }
}