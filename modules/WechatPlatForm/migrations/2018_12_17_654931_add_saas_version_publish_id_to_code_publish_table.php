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

class AddSaasVersionPublishIdToCodePublishTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('code_publish', function (Blueprint $table) {
            $table->integer('saas_version_publish_id')->nullable();
            $table->text('category')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('code_publish', function (Blueprint $table) {
            $table->dropColumn(['saas_version_publish_id','category']);
        });
    }
}
