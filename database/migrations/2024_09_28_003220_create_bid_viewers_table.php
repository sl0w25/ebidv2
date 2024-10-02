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
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->string('trans_no');
            $table->string('email_address');
            $table->string('company_name');
            $table->string('name_of_bidder_and/or_authorized_rrepresentative');
            $table->string('official_mobile_no');
            $table->string('project_title');
            $table->string('linkfile');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bid_viewers');
    }
};
