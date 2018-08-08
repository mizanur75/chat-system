<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Session;
use App\Http\Resources\ChatResource;
use App\Events\PrivatechatEvent;

class ChatController extends Controller
{
    public function send(Session $session, Request $request){
        $message = $session->messages()->create(['content'=>$request->content]);
        $chat=$message->chats()->create([
                'session_id' => $session->id,
                'type' => 0,
                'user_id' => auth()->id(),
            ]);
        $message->chats()->create([
            'session_id' => $session->id,
            'type' => 1,
            'user_id' => $request->to_user,
        ]);

        broadcast(new PrivatechatEvent($message->content,$chat));

        return response($message);
    }

    public function chats(Session $session){
        return ChatResource::collection($session->chats->where('user_id',auth()->id()));
    }
}
