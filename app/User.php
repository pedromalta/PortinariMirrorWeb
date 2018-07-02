<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends \TCG\Voyager\Models\User
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public static function boot()
    {
        parent::boot();

        self::updated(function($model){
            if ($model->avatar != 'users/default.png') {
                $path = "storage/" . $model->avatar;
                $pathinfo = pathinfo($path);
                $extension = $pathinfo['extension'];
                $known_face = '/known-faces/' . $model->id . "." . $extension;
                copy($path, $known_face);
            }
        });


        self::deleted(function($model){
            if ($model->avatar != 'users/default.png') {
                $path = "storage/" . $model->avatar;
                $pathinfo = pathinfo($path);
                $extension = $pathinfo['extension'];
                $known_face = '/known-faces/' . $model->id . "." . $extension;
                unlink($known_face);
                unlink($path);
            }
        });
    }
}
