<?php

namespace App\Policies;

use App\Plan;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlanPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

        /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Plan  $actividad
     * @return mixed
     */
    public function view(User $user,Plan $plan)
    {
        return $user->rol->nombre==="Decano";
    }
}
