<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int id
 * @property string name
 * @property string email
 * @property string password
 * @property Collection roles
 * @property Collection teams
 * @method Builder where($column, $operator = null, $value = null, $boolean = 'and')
 *
 * Class User
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * User UserMeta
     *
     * @return HasOne
     */
    public function meta()
    {
        return $this->hasOne(UserMeta::class);
    }

    /**
     * User Roles
     *
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if user has role
     *
     * @param  string  $role
     * @return boolean
     */
    public function hasRole($role)
    {
        $roles = array_column($this->roles->toArray(), 'name');
        return array_search($role, $roles) > -1;
    }

    /**
     * Check if user has permission
     *
     * @param  string  $permission
     * @return boolean
     */
    public function hasPermission($permission)
    {
        return $this->roles->map(function ($role) use ($permission) {
            if (in_array($permission, explode(',', $role->permissions))) {
                return true;
            }
            return false;
        })->contains(function ($value) {
            return $value === true;
        });
    }

    /**
     * Teams
     *
     * @return BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    /**
     * Team member
     *
     * @param integer $id
     * @return boolean
     */
    public function isTeamMember($id)
    {
        $teams = array_column($this->teams->toArray(), 'id');
        return array_search($id, $teams) > -1;
    }

    /**
     * Team admin
     *
     * @param integer $id
     * @return boolean
     */
    public function isTeamAdmin($id)
    {
        /** @var Team $team */
        $team = $this->teams->find($id);

        if ($team) {
            return (int) $team->user_id === (int) $this->id;
        }

        return false;
    }

    /**
     * Find by Email
     *
     * @param  string $email
     * @return User|null|object
     */
    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
