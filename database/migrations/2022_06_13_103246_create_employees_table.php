<?php

use App\Models\Employee;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role',['admin','support']);
            $table->timestamps();
        });

        Employee::create([
            'name' =>'Ashirbek',
            'email' =>'ashirbekrajapboyev@gmail.com',
            'password' =>Hash::make('12345'),
            'role' =>'admin'
        ]);

        Employee::create([
            'name' =>'Ashirbek',
            'email' =>'ashirbekrajapboyev1@gmail.com',
            'password' =>Hash::make('12345'),
            'role' =>'support'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
