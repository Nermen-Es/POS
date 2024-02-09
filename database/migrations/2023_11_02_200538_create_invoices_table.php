<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('spplier_id')->constrained('sppliers')->onDelete('cascade')->onUpdate('cascade');
            $table->string('invoice_number', 50)->nullable();
            $table->date('invoice_date')->nullable();
            $table->decimal('total_amount');
            $table->decimal('paid_amount')->nullable();
            $table->decimal('remaining_amount')->default(0);
            $table->enum('status', ['unPaid','Paid','Partially'])->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
