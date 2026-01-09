<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['code', 'name', 'type'];

    // 「この科目は、多くの仕訳明細で使われている」
    // ※元帳（Ledger）を作るときに使います
    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }
}