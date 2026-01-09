<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 論理削除用

class Transaction extends Model
{
    use SoftDeletes; // 論理削除を有効化

    // 書き込みを許可するカラムリスト（ホワイトリスト）
    protected $fillable = ['transaction_date', 'description'];

    // リレーション定義: Accessの「1対多」の「1」側
    // 「この取引は、複数の仕訳明細(JournalEntry)を持っている」
    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }
}