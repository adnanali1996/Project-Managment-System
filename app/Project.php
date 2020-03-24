<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //
    protected $fillable = [ 
    	'project_name'
    ] ;

    //A DEPARTMENT MAY HAVE MANY TASKS
    public function tasks() {
    	return $this->hasMany('App\Task');
    }
    //A DEPARTMENT MAY HAVE MANY UEERS
    public function users() {
        return $this->hasMany('App\User');
    }


}
