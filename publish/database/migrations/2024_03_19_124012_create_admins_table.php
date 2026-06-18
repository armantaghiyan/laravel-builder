<?php

use App\Core\Domain\Admin\Models\Faq;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::create(Faq::TB, function (Blueprint $table) {
			$table->id();
			$table->string(Faq::NAME)->nullable();
			$table->string(Faq::USERNAME)->unique();
			$table->string(Faq::PASSWORD)->nullable();
			$table->string(Faq::IMAGE, 100)->nullable();
			$table->dateTime(Faq::LAST_LOGIN)->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::dropIfExists(Faq::TB);
	}
};
