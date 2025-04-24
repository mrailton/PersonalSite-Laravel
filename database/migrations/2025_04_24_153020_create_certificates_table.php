<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('issuer');
            $table->date('issued_on');
            $table->date('expires_on')->nullable();
            $table->text('notes')->nullable();
            $table->text('attachment')->nullable();
            $table->text('attachment_name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
