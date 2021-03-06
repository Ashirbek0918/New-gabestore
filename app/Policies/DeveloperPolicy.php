<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Developer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeveloperPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Developer  $developer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view()
    {
        return Auth::user()->role == 'admin';
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create()
    {
        return Auth::user()->role == 'admin';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Developer  $developer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update()
    {
        return Auth::user()->role == 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Developer  $developer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete()
    {
        return Auth::user()->role == 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Developer  $developer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Developer $developer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Developer  $developer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Developer $developer)
    {
        //
    }
}
