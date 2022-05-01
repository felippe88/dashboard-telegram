<?php

namespace App\Http\Controllers;


use App\Models\Chat;
use App\Library\TelegramStart;
use Longman\TelegramBot\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $telegram;

    public function __construct()
    {
        $this->middleware('auth');
        $this->telegram = new TelegramStart;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        $chats = Chat::where('type','!=','private')
            ->where('status','1')
            ->get()
            ->toArray();
        
        $this->telegram->initialTelegram();
        
        foreach ($chats as $key => $chat) {
            $link = Request::exportChatInviteLink([
                'chat_id' => $chat['id']
            ]);
            $chats[$key]['link'] = $link->result;
            
        }
        
        return view('home',['chats' => $chats]);
    }


    


}
