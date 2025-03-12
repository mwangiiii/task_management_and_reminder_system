<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    // use AuditableTrait;
    
    protected $table = 'uploads';
    protected $fillable = [
        'paths',
        'task_id',
    ];

    public function task(){
        return $this->belongsTo(Task::class);
    }
}
