<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    // 書き込みを許可するカラムリスト
    protected $fillable = ['transaction_id', 'account_id', 'amount', 'entry_type'];

    // リレーション定義: Accessの「1対多」の「多」側
    // 「この明細は、ひとつの取引(Transaction)に属する」
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // 「この明細は、ひとつの勘定科目(Account)を使っている」
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}