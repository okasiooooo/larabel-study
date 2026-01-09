<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_journal_entries_table.php

public function up()
{
    Schema::create('journal_entries', function (Blueprint $table) {
        $table->id();
        
        // 【外部キー設定】
        // transactionsテーブルのidと紐付け。
        // constrained(): 整合性を担保 (存在しない取引IDは入れさせない)
        // onDelete('cascade'): 親(取引)が消えたら、子(明細)も道連れで消す設定
        $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
        
        // accountsテーブルのidと紐付け
        $table->foreignId('account_id')->constrained('accounts');
        
        // 金額 (最大10桁、小数部0桁 = 整数のみ)
        $table->decimal('amount', 10, 0);
        
        // 貸借区分 (借方: debit, 貸方: credit)
        $table->enum('entry_type', ['debit', 'credit']);
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
