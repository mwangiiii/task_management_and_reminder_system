<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recurrency extends Model
{
    // use \OwenIt\Auditing\Auditable;
    // use AuditableTrait;
    protected $table='recurrencies';
    protected $fillable =[
        'frequency',
    ];
    //

    public function task(){
        return $this->hasMany(Task::class);
    }
}

