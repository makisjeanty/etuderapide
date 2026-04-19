<?php

namespace App\Models;

use App\Traits\Auditable;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'is_admin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use Auditable, HasApiTokens, HasFactory, HasRoles, Notifiable;

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin || $this->hasRole('admin');
    }

    public function canViewDashboard(): bool
    {
        return $this->isAdmin() || $this->can('view-dashboard');
    }

    public function canAccessAdminPanel(): bool
    {
        return $this->canViewDashboard()
            || $this->canAny([
                'manage-users',
                'manage-leads',
                'manage-projects',
                'manage-posts',
                'manage-services',
            ]);
    }

    public function canManageUsers(): bool
    {
        return $this->isAdmin() || $this->can('manage-users');
    }

    public function canManagePosts(): bool
    {
        return $this->isAdmin() || $this->can('manage-posts');
    }

    public function canManageProjects(): bool
    {
        return $this->isAdmin() || $this->can('manage-projects');
    }

    public function canManageServices(): bool
    {
        return $this->isAdmin() || $this->can('manage-services');
    }

    public function canManageLeads(): bool
    {
        return $this->isAdmin() || $this->can('manage-leads');
    }

    public function apiAbilities(): array
    {
        $abilities = ['profile:read'];

        if ($this->canViewDashboard()) {
            $abilities[] = 'dashboard:read';
        }

        if ($this->canManageUsers()) {
            $abilities[] = 'users:manage';
        }

        if ($this->canManagePosts()) {
            $abilities[] = 'posts:manage';
        }

        if ($this->canManageProjects()) {
            $abilities[] = 'projects:manage';
        }

        if ($this->canManageServices()) {
            $abilities[] = 'services:manage';
        }

        if ($this->canManageLeads()) {
            $abilities[] = 'leads:manage';
        }

        return array_values(array_unique($abilities));
    }

    public function twoFactorCodes()
    {
        return $this->hasMany(TwoFactorCode::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }
}
