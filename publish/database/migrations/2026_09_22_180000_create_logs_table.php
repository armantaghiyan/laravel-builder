<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Core\Domain\Logger\Models\Log;

return new class extends Migration {

    public function up(): void {
        Schema::create(Log::TB, function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger(Log::USER_ID)->nullable()->index();
            $table->string(Log::USER_GUARD)->nullable()->index();
            $table->string(Log::EVENT)->index();
            $table->string(Log::LEVEL)->index();
            $table->text(Log::MESSAGE)->nullable();
            $table->nullableMorphs('loggable');
            $table->json(Log::METADATA)->nullable();
            $table->ipAddress(Log::IP_ADDRESS)->nullable()->index();
            $table->string(Log::USER_AGENT, 256)->nullable();
            $table->unsignedTinyInteger(Log::IS_REVIEWED)->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists(Log::TB);
    }
};
