<?php

use App\Models\User\Admin;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::create(Admin::TB, function (Blueprint $table) {
			$table->id();
			$table->string(Admin::NAME)->nullable();
			$table->string(Admin::USERNAME)->unique();
			$table->string(Admin::PASSWORD)->nullable();
			$table->string(Admin::IMAGE, 100)->nullable();
			$table->dateTime(Admin::LAST_LOGIN)->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::dropIfExists(Admin::TB);
	}
};
