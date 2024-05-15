<?php

namespace App\Livewire\Chat;

use Livewire\Component;

class ChatList extends Component
{

    public $selectedConvrsation;
    
    protected $listeners=['refresh'=>'$refresh'];

    public function render()
    {
        $user= auth()->user();
        return view('livewire.chat.chat-list'
            , [
                'conversations' => $user->conversations()->latest('updated_at')->get()
        
        ]);
    }
}
