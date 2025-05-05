<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create(TB_ADMINS, function (Blueprint $table) {
            $table->id();
            $table->string(COL_ADMIN_NAME)->nullable();
            $table->string(COL_ADMIN_USERNAME)->unique();
            $table->string(COL_ADMIN_PASSWORD)->nullable();
            $table->string(COL_ADMIN_IMAGE, 100)->nullable();
            $table->dateTime(COL_ADMIN_LAST_LOGIN)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists(TB_ADMINS);
    }
};
