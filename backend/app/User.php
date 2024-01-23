<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use \App\Models\Concerns\UseUuid;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get JWT Identifier token.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Add custom claims to JWT Token.
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role
        ];
    }

    /**
     * Encrypt user password for saving in database.
     */
    public function setPasswordAttribute($password)
    {
        if (!empty($password)) {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    /**
     * Get user settings.
     */
    public function userSettings()
    {
        return $this->hasOne('App\UserSettings');
    }

    /**
     * Get user food entries.
     */
    public function foodEntries()
    {
        return $this->hasMany('App\FoodEntry');
    }
}
