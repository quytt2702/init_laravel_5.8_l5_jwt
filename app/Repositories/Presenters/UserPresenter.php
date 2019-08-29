<?php

namespace App\Repositories\Presenters;

use App\Repositories\Transformers\UserTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class UserPresenter.
 *
 * @package namespace App\Repositories\Presenters;
 */
class UserPresenter extends FractalPresenter
{
    /**
     * @var string
     */
    protected $resourceKeyCollection = 'user';

    /**
     * @var string
     */
    protected $resourceKeyItem = 'user';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new UserTransformer();
    }
}
