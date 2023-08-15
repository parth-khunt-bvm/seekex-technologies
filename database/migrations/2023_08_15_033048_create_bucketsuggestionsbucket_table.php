<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBucketsuggestionsbucketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bucketsuggestionsbucket', function (Blueprint $table) {
            $table->id();
            $table->integer('bucketsuggestionsId');
            $table->string('bucket');
            $table->decimal('volume', 10,4)->default(0.0000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bucketsuggestionsbucket');
    }
}
