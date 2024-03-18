<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kids', function (Blueprint $table) {
            $table->string('name')->nullable()->change();;
            $table->integer('age')->nullable()->change();;
            $table->string('school')->nullable()->change();;
            $table->string('address')->nullable()->change();;
            $table->string('id_num')->nullable()->change();;
            $table->string('seat_number')->nullable()->change();;
            // Add other fields as needed
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('kids', function (Blueprint $table) {
            $table->dropColumn(['name', 'age', 'school', 'address', 'id_num', 'seat_number']);
            // Drop other fields if needed
        });
    }
};
