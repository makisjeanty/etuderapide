<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->text('internal_notes')->nullable()->after('message');
            $table->string('payment_link')->nullable()->after('internal_notes');
            $table->decimal('quoted_value', 10, 2)->nullable()->after('payment_link');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['internal_notes', 'payment_link', 'quoted_value']);
        });
    }
};
