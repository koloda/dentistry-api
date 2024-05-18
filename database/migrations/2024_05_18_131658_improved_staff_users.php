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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('clinic_id')->nullable()->after('id')->constrained('clinics');
            //add phone
            $table->string('phone')->after('email');
            $table->string('tmp_sms_code')->nullable()->after('phone');
            $table->timestamp('tmp_sms_code_expired_at')->nullable()->after('tmp_sms_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['clinic_id']);
            $table->dropColumn('clinic_id');
            $table->dropColumn('phone');
            $table->dropColumn('tmp_sms_code');
            $table->dropColumn('tmp_sms_code_expired_at');
        });
    }
};
