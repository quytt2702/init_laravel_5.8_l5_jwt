<?php

namespace App\Repositories\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Category;
use App\Traits\TransformerTrait;

/**
 * Class CategoryTransformer.
 *
 * @package namespace App\Repositories\Transformers;
 */
class CategoryTransformer extends TransformerAbstract
{
    use TransformerTrait;
    /**
     * Transform the Category entity.
     *
     * @param \App\Models\Category $model
     *
     * @return array
     */
    public function transform(Category $model)
    {
        return $this->parseTransformer($model);
    }
}
