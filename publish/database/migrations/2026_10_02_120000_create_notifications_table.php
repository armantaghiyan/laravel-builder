<?php

use App\Core\Domain\Notification\Models\Notification;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(Notification::TB, function (Blueprint $table): void {
            $table->id();
            $table->string(Notification::TITLE, 160);
            $table->text(Notification::MESSAGE);
            $table->string(Notification::URL, 2048)->nullable();
            $table->nullableMorphs('user');
            $table->unsignedTinyInteger(Notification::IS_READ)->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(Notification::TB);
    }
};
