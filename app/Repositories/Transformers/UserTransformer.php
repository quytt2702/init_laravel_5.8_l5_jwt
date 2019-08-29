<?php

namespace App\Repositories\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;
use App\Traits\TransformerTrait;

/**
 * Class UserTransformer.
 *
 * @package namespace App\Repositories\Transformers;
 */
class UserTransformer extends TransformerAbstract
{
    use TransformerTrait;

    /**
     * List of resources possible to include
     * @var array
     */
    protected $availableIncludes = [
        'posts'
    ];

    /**
     * Transform the User entity.
     *
     * @param \App\Models\User $model
     *
     * @return array
     */
    public function transform(User $model)
    {
        $removeFields = [
            'password_changed_at'
        ];

        $customFields = [];

        return $this->parseTransformer($model, $customFields, $removeFields);
    }


    /**
     * @param User $user
     * @return \League\Fractal\Resource\Collection
     */
    public function includePosts(User $user)
    {
        return $this->collection($user->posts, new PostTransformer, 'post');
    }
}
