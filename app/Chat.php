<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Message;

class Chat extends Model
{
    protected $guarded = [];
    
    public function message(){
        return $this->belongsTo(Message::class);
    }
}
