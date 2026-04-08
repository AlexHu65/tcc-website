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
        Schema::create('sensitive_form_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('form_handle');
            $table->string('submission_id')->nullable();
            $table->string('email_hash', 64)->nullable()->index();
            $table->longText('encrypted_payload');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensitive_form_submissions');
    }
};
