<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\User;
use Livewire\Component;

class Users extends Component
{


    //Message method
    public function message($UserId){
        
        $CurrntlyAuthUserID = auth()->user()->id;
        //Check if Ther Exsistn Conversation
        $ExsistingConverstion = Conversation::where(
                            function($query) use($UserId,$CurrntlyAuthUserID){
                                $query->where("sender_id" , $CurrntlyAuthUserID)
                                    ->where("receiver_id" , $UserId);
        })->orWhere(
                    function($query) use($UserId,$CurrntlyAuthUserID){
                        $query->where("sender_id" , $UserId)
                            ->where("receiver_id" , $CurrntlyAuthUserID);
        
                        })->first();
        
        if($ExsistingConverstion){
            return redirect()->route("chat.message" , ["query" => $ExsistingConverstion->id]);
        }

        //If not Create New Conversation
        $NewConversation = Conversation::create([
            "sender_id" => $CurrntlyAuthUserID,
            "receiver_id" => $UserId,
        ]);

        return redirect()->route("chat.message" , ["query" => $NewConversation->id ]);

    }
    public function render()
    {
        return view('livewire.users' , 
                    ["users" => User::where("id" , "<>" , auth()->user()->id)->get()
                ]);
    }
}
