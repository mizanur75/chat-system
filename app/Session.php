<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Chat;
use App\Message;

class Session extends Model
{
    protected $guarded =[];
    public function chats(){
        return $this->hasManyThrough(Chat::class,Message::class);
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }
}
