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

class CreateAuthorizersApplicationTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('authorizers_application', function (Blueprint $table) {

            $table->increments('id');

            $table->string('appid');

            $table->string('uuid')->nullable();

            $table->integer('application_id')->nullable();

            $table->string('application_type')->nullable();

            $table->string('saas_version_code')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('authorizers_application');
    }
}
