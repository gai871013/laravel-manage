<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\CardsRepository;
use App\Entities\Cards;
use App\Validators\CardsValidator;

/**
 * Class CardsRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CardsRepositoryEloquent extends BaseRepository implements CardsRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Cards::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
