<?php

namespace App\Policies\Admin;

use App\Models\Admin;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class GenrePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(Admin $user)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Genre  $genre
     * @return mixed
     */
    public function view(Admin $user, Genre $genre)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(Admin $user)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Genre  $genre
     * @return mixed
     */
    public function update(Admin $user, Genre $genre)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Genre  $genre
     * @return mixed
     */
    public function delete(Admin $user, Genre $genre=null)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Genre  $genre
     * @return mixed
     */
    public function restore(Admin $user, Genre $genre)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Genre  $genre
     * @return mixed
     */
    public function forceDelete(Admin $user, Genre $genre)
    {
        return Response::allow();
    }
}
