<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>仕訳入力</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 pb-32"> <div class="max-w-md mx-auto bg-white min-h-screen shadow-lg">
        
        <header class="bg-blue-600 text-white p-4 text-center font-bold text-lg">
            仕訳登録
        </header>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>・{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('transactions.store') }}" method="POST" class="p-4" 
              x-data="journalApp()"> @csrf <div class="mb-6 border-b pb-4">
                <label class="block text-sm font-bold mb-2">日付</label>
                <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}"
                       class="w-full p-3 border rounded bg-gray-50 text-lg">
                
                <label class="block text-sm font-bold mt-4 mb-2">全体の摘要 (メモ)</label>
                <input type="text" name="description" placeholder="スーパーで買い物など"
                       class="w-full p-3 border rounded bg-gray-50">
            </div>

            <div class="space-y-4">
                <label class="block text-sm font-bold text-gray-600">仕訳明細</label>

                <template x-for="(row, index) in rows" :key="index">
                    <div class="p-4 border rounded bg-gray-50 shadow-sm relative">
                        
                        <button type="button" @click="removeRow(index)" x-show="rows.length > 2"
                                class="absolute top-2 right-2 text-red-500 font-bold text-xl">
                            &times;
                        </button>

                        <div class="mb-2">
                            <label class="text-xs text-gray-500">科目</label>
                            <select :name="`entries[${index}][account_id]`" x-model="row.account_id"
                                    class="w-full p-2 border rounded bg-white">
                                <option value="">選択してください</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <div class="w-2/3">
                                <label class="text-xs text-gray-500">金額</label>
                                <input type="number" :name="`entries[${index}][amount]`" x-model.number="row.amount"
                                       class="w-full p-2 border rounded text-right" placeholder="0">
                            </div>
                            
                            <div class="w-1/3">
                                <label class="text-xs text-gray-500">貸借</label>
                                <select :name="`entries[${index}][type]`" x-model="row.type"
                                        class="w-full p-2 border rounded"
                                        :class="row.type === 'debit' ? 'bg-blue-50 text-blue-700' : 'bg-red-50 text-red-700'">
                                    <option value="debit">借方</option>
                                    <option value="credit">貸方</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="mt-4 text-center">
                <button type="button" @click="addRow()"
                        class="text-blue-600 border border-blue-600 px-4 py-2 rounded-full text-sm hover:bg-blue-50">
                    ＋ 明細行を追加 (諸口)
                </button>
            </div>

            <div class="fixed bottom-0 left-0 w-full bg-gray-800 text-white p-4 shadow-lg z-50">
                <div class="max-w-md mx-auto">
                    <div class="flex justify-between text-sm mb-2 border-b border-gray-600 pb-2">
                        <span>借方: <span x-text="formatMoney(totalDebit)"></span></span>
                        <span>貸方: <span x-text="formatMoney(totalCredit)"></span></span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <div class="text-lg font-bold">
                            差額: 
                            <span :class="diff === 0 ? 'text-green-400' : 'text-red-400'" 
                                  x-text="formatMoney(diff)"></span>
                        </div>
                        
                        <button type="submit" 
                                :disabled="diff !== 0 || totalDebit === 0"
                                :class="diff === 0 && totalDebit > 0 ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-500 cursor-not-allowed'"
                                class="px-6 py-2 rounded font-bold transition-colors">
                            保存
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <script>
        function journalApp() {
            return {
                // 初期データ: 最低2行用意しておく
                rows: [
                    { account_id: '', amount: '', type: 'debit' },
                    { account_id: '', amount: '', type: 'credit' }
                ],
                
                // 行を追加する関数
                addRow() {
                    this.rows.push({ account_id: '', amount: '', type: 'debit' });
                },

                // 行を削除する関数
                removeRow(index) {
                    this.rows.splice(index, 1);
                },

                // 借方合計の計算プロパティ
                get totalDebit() {
                    return this.rows
                        .filter(row => row.type === 'debit')
                        .reduce((sum, row) => sum + (Number(row.amount) || 0), 0);
                },

                // 貸方合計の計算プロパティ
                get totalCredit() {
                    return this.rows
                        .filter(row => row.type === 'credit')
                        .reduce((sum, row) => sum + (Number(row.amount) || 0), 0);
                },

                // 差額の計算
                get diff() {
                    return this.totalDebit - this.totalCredit;
                },

                // 金額表示用フォーマット (例: 1,000)
                formatMoney(value) {
                    return new Intl.NumberFormat('ja-JP').format(value);
                }
            }
        }
    </script>
</body>
</html>