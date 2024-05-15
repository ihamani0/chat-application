<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use Livewire\Component;

class Chat extends Component
{
    //this componnet hadne Chat list and Chat box he pass two agrument  $query and $selectedConvrsation to them 
    public $query;
    public $selectedConvrsation;



    
    public function mount()
    {
        $this->selectedConvrsation = Conversation::findOrFail($this->query);

        #mark message belogning to receiver as read 
        Message::where('conversation_id',$this->selectedConvrsation->id)
                    ->where('receiver_id',auth()->id())
                        ->whereNull('read_at')
                            ->update(['read_at'=>now()]);

    }
    
    public function render()
    {
        return view('livewire.chat.chat');
    }
}
