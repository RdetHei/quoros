<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email', 'username', 'password', 'role', 'is_banned', 'banned_until', 'ban_reason', 'profile_photo', 'bio', 'is_public_reading_list', 'profile_photo_url', 'profile_photo_public_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Override notifications relationship to use our custom InAppNotification model.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(InAppNotification::class)->latest();
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function readingHistories(): HasMany
    {
        return $this->hasMany(ReadingHistory::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function novels(): HasMany
    {
        return $this->hasMany(Novel::class, 'author_id');
    }

    public function novelRequests(): HasMany
    {
        return $this->hasMany(NovelRequest::class);
    }

    public function inAppNotifications(): HasMany
    {
        return $this->hasMany(InAppNotification::class);
    }

    public function reportsSubmitted(): HasMany
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function userLists(): HasMany
    {
        return $this->hasMany(UserList::class);
    }

    public function following(): HasMany
    {
        return $this->hasMany(AuthorFollow::class, 'follower_id');
    }

    public function followers(): HasMany
    {
        return $this->hasMany(AuthorFollow::class, 'author_id');
    }

    public function isFollowedBy(?int $userId): bool
    {
        if (! $userId) {
            return false;
        }

        return $this->followers()->where('follower_id', $userId)->exists();
    }

    public function canBeFollowed(): bool
    {
        return in_array($this->role, ['writer', 'admin'], true)
            || $this->novels()->exists();
    }

    public function isCurrentlyBanned(): bool
    {
        if ($this->role === 'admin') {
            return false;
        }

        if ($this->is_banned) {
            return true;
        }

        return $this->banned_until !== null && $this->banned_until->isFuture();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean',
            'banned_until' => 'datetime',
        ];
    }
}
