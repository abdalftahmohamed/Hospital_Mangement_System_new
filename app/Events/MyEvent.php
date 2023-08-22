<?php

namespace App\Events;

use App\Models\Patient;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

#first implement  ShouldBroadcast
class MyEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $patient_id;
    public $doctor_id;
    public $Service_id;
    public $price;
    public $discount_value;
    public $tax_rate;

    public function __construct($data)
    {
        $pa=Patient::find($data['patient_id']);
        $this->patient_id =$pa->name;
        $this->doctor_id = $data['doctor_id'];
        $this->Service_id = $data['Service_id'];
        $this->price = $data['price'];
        $this->discount_value = $data['discount_value'];
        $this->tax_rate = $data['tax_rate'];
    }
    public function broadcastOn()
    {
        return ['my-channel'];
    }
}
