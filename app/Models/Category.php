<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // use \OwenIt\Auditing\Auditable;
    // use AuditableTrait;

    protected $table = 'categories';
    protected $fillable = [
        'type',
        
    ];

    
    public function task(){
        return $this->hasMany(Task::class);
    }
}
