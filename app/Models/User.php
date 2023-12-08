<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function getEmailSingle($email)
    {
        return User::where('email', '=', $email)->first();
    }

    public static function getAdmin()
    {
        $query = self::select('users.*')
            ->where('user_types', '=', 1)
            ->where('is_deleted', '=', 0);

        if (!empty(request()->get('email'))) {
            $query = $query->where('email', 'like', '%' . request()->get('email') . '%');
        }

        if (!empty(request()->get('name'))) {
            $query = $query->where('name', 'like', '%' . request()->get('name') . '%');
        }

        return $query->orderBy('id', 'asc')->paginate(10);
    }

    public static function getSingle($id)
    {
        return self::find($id);
    }

    public static function getTokenSingle($remember_token)
    {
        return User::where('remember_token', '=', $remember_token)->first();
    }
}