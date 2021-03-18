<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\UsersRepository;
use App\Entities\Users;
use App\Validators\UsersValidator;

/**
 * Class UsersRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class UsersRepositoryEloquent extends BaseRepository implements UsersRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Users::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
