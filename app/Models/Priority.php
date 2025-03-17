<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    protected $table='priority_statuses';
    protected $fillable=['priority_status'];

    public function task(){
        return $this->belongsTo(Task::class);
    }
}
