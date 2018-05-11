<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentIncome extends Model
{

    protected $fillable = [
        'id', 'user_id', 'date', 'agent_id', 'ratio', 'monthly_income', 'income'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];

    //推荐人
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
