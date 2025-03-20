<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // use \OwenIt\Auditing\Auditable;
    //use AuditableTrait;
    
    protected $table ='tasks';
    protected $fillable = [
       
        'name',
        'budget',
        'repeat',
        'description',
        'alert_id',
        'category_id',
        'recurrency_id',
        'currency',
        'cost',
        'parent_task_id',
        'completion_status_id',
        'start_date',
        'due_date',
        'user_id',
        'parent_task_id',
        'deleted',
        
    ];
    protected $casts = [
        'start_date' => 'datetime',
        'due_date' => 'datetime',
    ];

    public function alert(){
        return $this->hasMany(Alert::class);
    }


    public function category(){

        return $this->belongsTo(Category::class);
    }


    public function completionStatus(){
        return $this->belongsTo(CompletionStatus::class);
    }

    public function notification(){
        return $this->hasMany(Notification::class);
    }

    public function recurrency(){
        return $this->belongsTo(Recurrency::class);
    }

    public function uploads(){
        return $this->hasMany(Upload::class);
    }

    public function parentTask()
{
    return $this->belongsTo(Task::class, 'parent_task_id');
}



public function childTasks()
{
    return $this->hasMany(Task::class, 'parent_task_id');
}

public function user(){
    return $this->belongsTo(User::class);
}

public function priority(){
    return $this->hasOne(Priority::class, 'priority_statuses_id');
}


}


