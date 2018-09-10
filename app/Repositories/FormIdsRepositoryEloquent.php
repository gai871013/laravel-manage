<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\FormIdsRepository;
use App\Entities\FormIds;
use App\Validators\FormIdsValidator;

/**
 * Class FormIdsRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class FormIdsRepositoryEloquent extends BaseRepository implements FormIdsRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return FormIds::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
