<?php

namespace App\Repositories\Presenters;

use App\Repositories\Transformers\PostTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PostPresenter.
 *
 * @package namespace App\Repositories\Presenters;
 */
class PostPresenter extends FractalPresenter
{
    /**
     * @var string
     */
    protected $resourceKeyCollection = 'post';

    /**
     * @var string
     */
    protected $resourceKeyItem = 'post';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PostTransformer();
    }
}
