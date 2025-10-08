<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jenkins_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_name');
            $table->string('status')->nullable();
            $table->longText('log')->nullable();
            $table->timestamp('triggered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenkins_jobs');
    }
};
