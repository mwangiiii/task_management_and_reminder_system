<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Alert extends Model
{
    // use \OwenIt\Auditing\Auditable;
    // use AuditableTrait;

    protected $fillable=[
        'time_of_alert',
        'task_id',
        'alert_sent',
    ];
    protected $table= 'alerts';



    public function task(){
        return $this->belongsTo(Task::class);
    }


}
