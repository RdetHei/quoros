<?php

namespace App\Policies;

use App\Models\Chapter;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChapterPolicy
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
     * Determine whether the user can update the chapter.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chapter  $chapter
     * @return bool
     */
    public function update(User $user, Chapter $chapter)
    {
        return $user->id === $chapter->novel->author_id;
    }

    /**
     * Determine whether the user can delete the chapter.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chapter  $chapter
     * @return bool
     */
    public function delete(User $user, Chapter $chapter)
    {
        return $user->id === $chapter->novel->author_id;
    }
}
