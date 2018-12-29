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

use iBrand\Wechat\Platform\Models\SaasVersion;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class SaasVersionRepository.
 */
class SaasVersionRepository extends BaseRepository
{
    public function model()
    {
        return SaasVersion::class;
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function getAll($limit = 20,$code='')
    {

        $query = $this->model;

        if ($code) {
            $query = $query->where('code', 'like', '%'.$code.'%');
        }

        return $query->orderBy('updated_at', 'desc')->paginate($limit);
    }

}
