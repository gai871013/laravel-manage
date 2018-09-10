<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\MiniProgramTokensRepository;
use App\Entities\MiniProgramTokens;
use App\Validators\MiniProgramTokensValidator;

/**
 * Class MiniProgramTokensRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class MiniProgramTokensRepositoryEloquent extends BaseRepository implements MiniProgramTokensRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MiniProgramTokens::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
