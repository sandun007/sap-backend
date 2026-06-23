<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use transaction to ensure data integrity
        DB::transaction(function () {
            // Get all purchases sorted by creation time
            $purchases = DB::table('purchases')->orderBy('id', 'asc')->get();

            $counter = 1;
            foreach ($purchases as $purchase) {
                $oldId = $purchase->purchase_id;
                $newId = 'GRN-' . str_pad($counter, 6, '0', STR_PAD_LEFT);

                // Skip if already in new format and matches counter to prevent corruption if rerun
                if ($oldId === $newId) {
                    $counter++;
                    continue;
                }

                // 1. Update purchase_id in purchases
                DB::table('purchases')
                    ->where('id', $purchase->id)
                    ->update(['purchase_id' => $newId]);

                // 2. Update purchase_items matching this record
                DB::table('purchase_items')
                    ->where('purchase_record_id', $purchase->id)
                    ->update(['purchase_id' => $newId]);

                // 3. Update stock_transactions reference_number
                DB::table('stock_transactions')
                    ->where('reference_number', $oldId)
                    ->update(['reference_number' => $newId]);

                // 4. Update stock_transactions notes containing the old ID
                // Note format examples:
                // "Purchased via GRN: GRN-837117-JQZW from Supplier ..."
                // "Batch Adj [GRN: GRN-837117-JQZW]: reason"
                $transactions = DB::table('stock_transactions')
                    ->where('notes', 'like', "%{$oldId}%")
                    ->get();

                foreach ($transactions as $t) {
                    $updatedNotes = str_replace($oldId, $newId, $t->notes);
                    DB::table('stock_transactions')
                        ->where('transaction_id', $t->transaction_id)
                        ->update(['notes' => $updatedNotes]);
                }

                $counter++;
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op or revert isn't strictly necessary or easily reversible to random string format
    }
};
