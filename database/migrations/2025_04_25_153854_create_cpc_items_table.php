<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cpc_items', function (Blueprint $table): void {
            $table->id();
            $table->string('item_type');
            $table->date('date');
            $table->string('name');
            $table->text('topics');
            $table->text('key_learning_outcomes');
            $table->integer('points');
            $table->text('practice_change');
            $table->text('attachment');
            $table->text('attachment_name');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpc_items');
    }
};
