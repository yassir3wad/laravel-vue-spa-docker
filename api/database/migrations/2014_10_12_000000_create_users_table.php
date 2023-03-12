<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('users', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('username')->unique();
			$table->string('email')->unique();
			$table->string('mobile')->nullable();
			$table->string('image')->nullable();
			$table->date('dob')->nullable();
			$table->text('bio')->nullable();
			$table->timestamp('email_verified_at')->nullable();
			$table->string('password');
			$table->enum('status', ['active', 'inactive']);
			$table->rememberToken();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('users');
	}
};
