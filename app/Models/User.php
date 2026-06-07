<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email', 'password', 'role', 'is_banned'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_USER = 'user';
    public const ROLE_TRANSLATOR = 'translator';
    public const ROLE_ADMIN = 'admin';

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
        ];
    }

    public function isTranslator(): bool
    {
        return $this->role === self::ROLE_TRANSLATOR;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function favoriteTitles(): BelongsToMany
    {
        return $this->belongsToMany(Title::class, 'favorites');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function uploadedChapters(): HasMany
    {
        return $this->hasMany(Chapter::class, 'uploaded_by');
    }

    public function isBanned(): bool
    {
        return $this->is_banned;
    }

    public function ban(): void
    {
        $this->update(['is_banned' => true]);
    }

    public function unban(): void
    {
        $this->update(['is_banned' => false]);
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function assignRole(string $role): void
    {
        if (in_array($role, [self::ROLE_USER, self::ROLE_TRANSLATOR, self::ROLE_ADMIN])) {
            $this->update(['role' => $role]);
        }
    }
}
