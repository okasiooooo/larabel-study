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
    Schema::create('accounts', function (Blueprint $table) {
        $table->id(); // 自動採番の主キー (Accessのオートナンバー)
        $table->string('code')->unique(); // 科目コード (重複不可)
        $table->string('name'); // 科目名 (Short Text)
        // BS(貸借対照表)かPL(損益計算書)か、資産・負債などの区分
        $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
        $table->timestamps(); // 作成日時(created_at)と更新日時(updated_at)を自動生成
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
