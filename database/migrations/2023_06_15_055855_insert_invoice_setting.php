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
        DB::table('invoice_settings')->insert(
            array(
                'text' => 'please edit invoice settings'
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('invoice_settings')->truncate();
    }
};
