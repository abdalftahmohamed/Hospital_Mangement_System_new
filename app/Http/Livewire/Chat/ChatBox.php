<?php

namespace App\Http\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Doctor;
use App\Models\Message;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatBox extends Component
{
//    protected $listeners = ['load_conversationDoctor','load_conversationPatient','pushMessage'];
    public $receiver;
    public $selected_conversation;
    public $receviverUser;
    public $messages;
    public $auth_email;
    public $auth_id;
    public $event_name;
    public $chat_page;

    public function mount()
    {
        if (Auth::guard('patient')->check()) {
            $this->auth_email = Auth::guard('patient')->user()->email;
            $this->auth_id = Auth::guard('patient')->user()->id;
        } else {
            $this->auth_email = Auth::guard('doctor')->user()->email;
            $this->auth_id = Auth::guard('doctor')->user()->id;
        }
    }

    public function getListeners()
    {
        if (Auth::guard('patient')->check()) {
            $auth_id = Auth::guard('patient')->user()->id;
            $this->event_name = "MessageSend2";
            $this->chat_page = "chat2";

        } else {
            $auth_id = Auth::guard('doctor')->user()->id;
            $this->event_name = "MessageSend";
            $this->chat_page = "chat";
        }

        return [
            "echo-private:$this->chat_page.{$auth_id},$this->event_name" => 'broadcastMassage', 'load_conversationPatient', 'load_conversationDoctor', 'pushMessage'
        ];
    }


    public function broadcastMassage($event)
    {

        $broadcastMessage = Message::find($event['message_id']);
        $broadcastMessage->read = 1;
        $broadcastMessage->save();
        if ($this->selected_conversation){
            $this->pushMessage($broadcastMessage->id);
        }
        $this->emitTo('chat.chat-list','refresh');
    }


    public function pushMessage($messageId)
    {
        $newMessage = Message::find($messageId);
        $this->messages->push($newMessage);
    }


    public function load_conversationDoctor(Conversation $conversation, Doctor $receiver)
    {

        $this->selected_conversation = $conversation;
        $this->receviverUser = $receiver;
        $this->messages = Message::where('conversation_id', $this->selected_conversation->id)->get();
    }

    public function load_conversationPatient(Conversation $conversation, Patient $receiver)
    {

        $this->selected_conversation = $conversation;
        $this->receviverUser = $receiver;
        $this->messages = Message::where('conversation_id', $this->selected_conversation->id)->get();
    }


    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}
