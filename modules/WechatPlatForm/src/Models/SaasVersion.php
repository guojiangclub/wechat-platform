<?php

/*
 * This file is part of ibrand/wechat-platform.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Wechat\Platform\Models;

use Illuminate\Database\Eloquent\Model;

class SaasVersion extends Model
{
    protected $table = 'saas_version';

    protected $guarded = ['id'];

    public function publish()
    {
        return $this->hasMany(SaasVersionPublish::class,'saas_version_code','code');

    }
}
