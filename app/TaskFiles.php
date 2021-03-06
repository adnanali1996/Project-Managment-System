<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskFiles extends Model
{
    protected $fillable = ['task_id', 'filename', 'user_id'];
    
    public function task()
    {
        return $this->belongsTo('App\Task');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }


}
