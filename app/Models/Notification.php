<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model


{
    // use \OwenIt\Auditing\Auditable;
    // use AuditableTrait;
    
    protected $table ='notifications';
    protected $fillable =[
        'channel',
        'task_id',
        'content',
    ];
    //

    public function task(){
        return $this->belongsTo(Task::class);
    }
}
