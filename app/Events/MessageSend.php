<?php

namespace App\Events;

use App\Models\Conversation;
use App\Models\Doctor;
use App\Models\Message;
use App\Models\Patient;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSend implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $sender;
    public $message;
    public $receiver;
    public $conversation;

    public function __construct(Patient $sender,Message $message,Conversation $conversation,Doctor $receiver)
    {
        $this->sender=$sender;
        $this->message=$message;
        $this->conversation=$conversation;
        $this->receiver=$receiver;
    }

    public function broadcastWith(){
    return[
        'sender_email'=>$this->sender->email,
        'receiver_email'=>$this->receiver->email,
        'conversation_id'=>$this->conversation->id,
        'message_id'=>$this->message->id,
    ];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.'.$this->receiver->id);
    }
}
