<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('team_user', function (Blueprint $table) {
            $table->foreignIdFor(Team::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->json('commission')->nullable();
            $table->primary(['user_id', 'team_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_user');
    }
};
