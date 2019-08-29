<?php

namespace App\Repositories\Eloquents;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\UserRepository;
use App\Models\User;
use App\Repositories\Presenters\UserPresenter;
use Carbon\Carbon;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquents;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    /**
     * @return string
     */
    public function presenter()
    {
        return UserPresenter::class;
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param $password
     * @return mixed
     */
    public function updatePassword($password)
    {
        $user = $this->skipPresenter()->update([
            'password' => bcrypt($password),
            'password_changed_at' => Carbon::now(),
        ], auth()->id());

        auth()->logout();
        $newToken = auth()->login($user);

        return $newToken;
    }
}
