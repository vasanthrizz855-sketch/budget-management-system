<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table): void {
            $table->id();
            $table->string('customer_code')->unique();
            $table->string('customer_name');
            $table->string('email')->nullable()->unique();
            $table->string('phone', 20)->nullable()->index();
            $table->text('address')->nullable();
            $table->string('gst_number', 50)->nullable()->unique();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

