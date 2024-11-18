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
        Schema::table('ficheroes', function (Blueprint $table) {
            $table->string('description')->nullable()->after('path'); // Añade el campo `description`.
            $table->string('tags')->nullable()->after('description'); // Añade el campo `tags`.
            $table->string('author')->nullable()->after('tags'); // Añade el campo `author`.
        });
    }

    public function down(): void
    {
        Schema::table('ficheroes', function (Blueprint $table) {
            if (Schema::hasColumn('ficheroes', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('ficheroes', 'tags')) {
                $table->dropColumn('tags');
            }
            if (Schema::hasColumn('ficheroes', 'author')) {
                $table->dropColumn('author');
            }
        });
    }
    

};
