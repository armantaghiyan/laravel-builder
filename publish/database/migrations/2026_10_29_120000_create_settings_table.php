<?php

use App\Core\Domain\Setting\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create(Setting::TB, function (Blueprint $table): void {
            $table->string(Setting::KEY, 100)->primary();
            $table->json(Setting::VALUE);
        });
    }

    public function down(): void {
        Schema::dropIfExists(Setting::TB);
    }
};
