<?php

use App\Models\Developer;
use App\Models\Publisher;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_img');
            $table->double('rating');
            $table->double('first_price');
            $table->double('discount')->nullable();
            $table->double('discount_price')->nullable();
            $table->bigInteger('purchased_games')->default(0);
            $table->mediumText('about');
            $table->json('minimal_system');
            $table->json('recommended_system');
            $table->boolean('warn')->default(true);
            $table->text('warn_text');
            $table->json('screenshots');
            $table->json('trailers');
            $table->string('language');
            $table->string('location');
            $table->foreignIdFor(Publisher::class);
            $table->foreignIdFor(Developer::class);
            $table->string('platform');
            $table->json('release');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
