<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompletionStatus extends Model
{
    // use \OwenIt\Auditing\Auditable;
    protected $table = 'completion__statuses';
    protected $fillable = [
        'status',
    ];

    public function task(){
        return $this->hasMany(Task::class);
    }
}
