<?php

namespace App\Policies;

use App\Models\Novel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NovelPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user, $ability)
    {
        if ($user->role === 'admin') {
            return true;
        }
    }

    /**
     * Determine whether the user can update the novel.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Novel  $novel
     * @return bool
     */
    public function update(User $user, Novel $novel)
    {
        return $user->id === $novel->author_id;
    }

    /**
     * Determine whether the user can delete the novel.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Novel  $novel
     * @return bool
     */
    public function delete(User $user, Novel $novel)
    {
        return $user->id === $novel->author_id;
    }

    /**
     * Determine whether the user can manage chapters for the novel.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Novel  $novel
     * @return bool
     */
    public function manageChapters(User $user, Novel $novel)
    {
        return $user->id === $novel->author_id;
    }
}
