<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            // numeric PK to match your users/games numeric IDs
            $table->bigIncrements('id');

            // numeric FKs
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('admin_id')->nullable();

            $table->enum('type', ['rental', 'sale']);
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->decimal('price', 10, 2)->default(0);

            $table->timestamp('rented_at')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->timestamp('sold_at')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            // foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('restrict');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');

            // indexes
            $table->index('type');
            $table->index('status');
            $table->index('user_id');
            $table->index('game_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
