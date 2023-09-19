<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassType extends Model
{
    use HasFactory;

    public function scheduleClasses()
    {
        return $this->hasMany(ScheduleClass::class);
    }
}
