<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('tasks', function (Blueprint $table) {
        $table->timestamp('started_at')->nullable()->after('due_date');
        $table->timestamp('completed_at')->nullable()->after('started_at');
    });
}

public function down()
{
    Schema::table('tasks', function (Blueprint $table) {
        $table->dropColumn('started_at');
        $table->dropColumn('completed_at');
    });
}
};
