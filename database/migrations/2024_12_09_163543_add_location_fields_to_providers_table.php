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
        Schema::table('providers', function (Blueprint $table) {
            $table->string('country')->nullable()->after('phone');
            $table->string('region')->nullable()->after('country');
            $table->string('city')->nullable()->after('region');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->dropColumn(['country', 'region', 'city']);
        });
    }
};
