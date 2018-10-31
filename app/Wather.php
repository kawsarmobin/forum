<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wather extends Model
{
    protected $fillable = ['user_id', 'discussion_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function discussion()
    {
        return $this->belongsTo('App\Discussion');
    }
}
