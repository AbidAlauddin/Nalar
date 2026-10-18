<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use InvalidArgumentException;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = UserRole::SuperAdmin->value;

    public const ROLE_TEACHER = UserRole::Teacher->value;

    public const ROLE_STUDENT = UserRole::Student->value;

    /**
     * @var list<string>
     */
    public const ROLES = [
        self::ROLE_SUPER_ADMIN,
        self::ROLE_TEACHER,
        self::ROLE_STUDENT,
    ];

    /**
     * Default attributes for new user instances.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'role' => self::ROLE_STUDENT,
    ];

    protected static function booted(): void
    {
        static::saving(function (self $user): void {
            if ($user->role === null || $user->role === '') {
                $user->role = self::ROLE_STUDENT;
            }

            if (! in_array($user->role, self::ROLES, true)) {
                throw new InvalidArgumentException("Nilai role [{$user->role}] tidak valid.");
            }
        });
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isTeacher(): bool
    {
        return $this->role === self::ROLE_TEACHER;
    }

    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    public function assignRole(UserRole|string $role): self
    {
        $value = $role instanceof UserRole ? $role->value : $role;

        if (! in_array($value, self::ROLES, true)) {
            throw new InvalidArgumentException("Nilai role [{$value}] tidak valid.");
        }

        $this->forceFill(['role' => $value]);

        return $this;
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'user_id');
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
        ];
    }
}
