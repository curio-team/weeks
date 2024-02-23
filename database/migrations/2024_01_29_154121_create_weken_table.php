<?php

use App\Models\Semester;
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
        Schema::create('weken', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Semester::class);
            $table->date('maandag');
            $table->integer('nummer');
            $table->string('naam')->nullable();
            $table->enum('type', ['lesweek', 'bufferweek', 'vakantie'])->default('lesweek');
            $table->integer('cohort')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weken');
    }
};
