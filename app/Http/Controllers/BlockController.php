<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Session;
use App\Events\BlockEvent;

class BlockController extends Controller
{
    public function block(Session $session){
        $session->block = true;
        $session->blocked_by = auth()->id();
        $session->save();

        broadcast(new BlockEvent($session->id,true));
        return response(null,201);
    }

    public function unblock(Session $session){
        $session->block = false;
        $session->blocked_by = null;
        $session->save();
        broadcast(new BlockEvent($session->id,false));
        return response(null,201);
    }
}
