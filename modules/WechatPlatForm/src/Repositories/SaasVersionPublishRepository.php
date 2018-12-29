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

use iBrand\Wechat\Platform\Models\SaasVersionPublish;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class SaasVersionRepository.
 */
class SaasVersionPublishRepository extends BaseRepository
{
    public function model()
    {
        return SaasVersionPublish::class;
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function getAll($limit = 20,$code='')
    {

        $query = $this->model;

        if ($code) {
            $query = $query->where('saas_version_code',$code);
        }

        return $query->orderBy('created_at', 'desc')->paginate($limit);
    }


}
