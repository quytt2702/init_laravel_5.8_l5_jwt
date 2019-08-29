<?php

namespace App\Repositories\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Post;
use App\Traits\TransformerTrait;

/**
 * Class PostTransformer.
 *
 * @package namespace App\Repositories\Transformers;
 */
class PostTransformer extends TransformerAbstract
{
    use TransformerTrait;

    /**
     * List of resources possible to include
     * @var array
     */
    protected $availableIncludes = [
        'category', 'userCreated'
    ];

    /**
     * Transform the Post entity.
     *
     * @param \App\Models\Post $model
     *
     * @return array
     */
    public function transform(Post $model)
    {
        return $this->parseTransformer($model);
    }

    /**
     * @param Post $post
     * @return \League\Fractal\Resource\Item
     */
    public function includeUserCreated(Post $post)
    {
        return $this->item($post->userCreated, new UserTransformer, 'user');
    }

    /**
     * @param Post $post
     * @return \League\Fractal\Resource\Item
     */
    public function includeCategory(Post $post)
    {
        return $this->item($post->category, new CategoryTransformer, 'category');
    }
}
