<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->date('transaction_date'); // 取引日
        $table->string('description')->nullable(); // 全体の摘要 (NULL許可)
        $table->timestamps();
        $table->softDeletes(); // 論理削除 (deleted_atカラム追加)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
