<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Message;

class Chat extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'read_at' => 'datetime'
    ];
    public function message(){
        return $this->belongsTo(Message::class);
    }
}
