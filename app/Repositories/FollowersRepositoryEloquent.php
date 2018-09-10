<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\FollowersRepository;
use App\Entities\Followers;
use App\Validators\FollowersValidator;

/**
 * Class FollowersRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class FollowersRepositoryEloquent extends BaseRepository implements FollowersRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Followers::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
