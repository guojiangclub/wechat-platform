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

class CreateSaasVersionTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('saas_version', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('status')->default(1);

            $table->string('type');

            $table->string('name');

            $table->string('code');

            $table->string('title')->nullable();

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('saas_version');
    }
}
