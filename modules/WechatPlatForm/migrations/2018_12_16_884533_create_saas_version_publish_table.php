<?php

/*
 * This file is part of ibrand/wechat-platform.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaasVersionPublishTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('saas_version_publish', function (Blueprint $table) {

            $table->increments('id');

            $table->string('saas_version_code');

            $table->integer('status')->default(1);

            $table->string('name');

            $table->string('title')->nullable();


            $table->string('template_id');


            $table->string('mini_address')->nullable();

            $table->string('mini_title')->nullable();

            $table->string('mini_tag')->nullable();


            $table->text('description')->nullable();

            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('saas_version_publish');
    }
}
