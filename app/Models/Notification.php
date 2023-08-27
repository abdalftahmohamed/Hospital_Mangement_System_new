<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $guarded=[];


    public function scopeCountNotification($query,$user_id)
    {
        $query->where([['user_id',$user_id],['reader_status',0]]);
    }
}
