<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'level', 'parent_id', 'monthly_income', 'total_income'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //推荐人
    public function referee()
    {
        return $this->belongsTo('App\Models\User', 'parent_id')->select(['id', 'level', 'parent_id', 'monthly_income', 'total_income']);
    }
}
