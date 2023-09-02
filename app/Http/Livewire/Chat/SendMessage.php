<?php

namespace App\Http\Livewire\Chat;

use App\Events\MessageSend;
use App\Models\Conversation;
use App\Models\Doctor;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SendMessage extends Component
{

    public $body;
    public $selected_conversation;
    public $receviverUser;
    public $auth_email;
    public $sender;
    public $createdMessage;
    protected $listeners = ['updateMessage','dispatchSendMessage'];

    public function mount()
    {
        if (Auth::guard('patient')->check()) {
            $this->auth_email = Auth::guard('patient')->user()->email;
            $this->sender = Auth::guard('patient')->user();
        } else {
            $this->auth_email = Auth::guard('doctor')->user()->email;
            $this->sender = Auth::guard('doctor')->user();
        }
    }


    public function updateMessage(Conversation $conversation, Doctor $receiver){
        $this->selected_conversation = $conversation;
        $this->receviverUser = $receiver;
    }


    public function sendMessage()
    {

        if($this->body == null){

            return null;
        }

        $this->createdMessage = Message::create([
            'conversation_id' => $this->selected_conversation->id,
            'sender_email' => $this->auth_email,
            'receiver_email' => $this->receviverUser->email,
            'body' => $this->body,
        ]);
        $this->selected_conversation->last_time_message = $this->createdMessage->created_at;
        $this->selected_conversation->save();
        $this->reset('body');
        $this->emitTo('chat.chat-box','pushMessage',$this->createdMessage->id);
        $this->emitTo('chat.chat-list','refresh');

        $this->emitSelf('dispatchSendMessage');
    }

    public function dispatchSendMessage(){
        broadcast(new MessageSend(
            $this->sender,
            $this->createdMessage,
            $this->selected_conversation,
            $this->receviverUser
        ));
    }



    public function render()
    {
        return view('livewire.chat.send-message');
    }
}
