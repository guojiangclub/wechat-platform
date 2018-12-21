<?php

/*
 * This file is part of ibrand/wechat-platform.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Wechat\Platform\Repositories;

use iBrand\Wechat\Platform\Models\AuthorizerApplication;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class AuthorizerApplicationRepository.
 */
class AuthorizerApplicationRepository extends BaseRepository
{
    public function model()
    {
        return AuthorizerApplication::class;
    }


}
