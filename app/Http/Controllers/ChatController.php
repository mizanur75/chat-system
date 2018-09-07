<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Session;
use App\Http\Resources\ChatResource;
use App\Events\PrivatechatEvent;
use App\Events\MessageReadEvent;
use Carbon\Carbon;

class ChatController extends Controller
{
    public function send(Session $session, Request $request){
        $message = $session->messages()->create(['content'=>$request->content]);
        $chat=$message->chats()->create([
                'session_id' => $session->id,
                'type' => 0,
                'user_id' => auth()->id(),
            ]);// For sending
            $message->chats()->create([
                'session_id' => $session->id,
                'type' => 1,
                'user_id' => $request->to_user,
            ]);//for receiving

        broadcast(new PrivatechatEvent($message->content,$chat));

        return response($chat->id, 200);
    }

    public function chats(Session $session){
        return ChatResource::collection($session->chats->where('user_id',auth()->id()));
    }

    public function read(Session $session){
        $chats = $session->chats->where('read_at',null)->where('type',0)->where('user_id','!=',auth()->id());
        foreach ($chats as $chat) {
            $chat->update(['read_at' => Carbon::now()]);
            broadcast(new MessageReadEvent(new ChatResource($chat),$chat->session_id));
        }
    }
    public function clear(Session $session){
        $session->chats()->where('user_id',auth()->id())->delete();
        $session->chats->count() == 0 ? $session->messages()->delete() : "";
        return response('cleared',200);
    }
}
