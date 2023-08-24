<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $guarded=[];


    public function scopeCountNotification($query,$username)
    {
        $query->where([['username',$username],['reader_status',0]]);
    }
}
