<?php

namespace App\Policies;

use App\Actividad;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActividadPolicy
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
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Actividad  $actividad
     * @return mixed
     */
    public function view(User $user, Actividad $actividad)
    {
        return $user->rol->nombre==="Decano";
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //return $user->id==="1";
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Actividad  $actividad
     * @return mixed
     */
    public function update(User $user, Actividad $actividad)
    {
         //Revisa si el usuario autenticado es el mismo que va a modificar
         return $user->id === $actividad->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Actividad  $actividad
     * @return mixed
     */
    public function delete(User $user, Actividad $actividad)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Actividad  $actividad
     * @return mixed
     */
    public function restore(User $user, Actividad $actividad)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Actividad  $actividad
     * @return mixed
     */
    public function forceDelete(User $user, Actividad $actividad)
    {
        //
    }
}
