<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use App\Notifications\MessageRead;
use Livewire\Component;
use App\Notifications\MessageSent;

class ChatBox extends Component
{
    public $selectedConvrsation;
    public $body;
    public $loadedMessages;


    public $paginate_var = 10;

    protected $listeners = [
        'loadMore',
    ];


    public function getListeners()
    {
        $auth_id = auth()->user()->id;

        return [

            'loadMore',
            "echo-private:users.{$auth_id},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'broadcastedNotifications'

        ];
    }

    public function broadcastedNotifications($event)
    {
        if ($event['type'] == MessageSent::class) {



            if ($event['conversation_id'] == $this->selectedConvrsation->id) {



                $this->dispatch('scroll-bottom');

                $newMessage = Message::find($event['message_id']);




                #push message
                $this->loadedMessages->push($newMessage);


                #mark as read
                $newMessage->read_at = now();
                $newMessage->save();

                #broadcast 
                $this->selectedConvrsation->getReciver()
                    ->notify(new MessageRead($this->selectedConvrsation->id));
            }
        }
    }


    public function loadMore(): void
    {


        #increment 
        $this->paginate_var += 10;

        #call loadMessages()
        $this->loadMessages();

        #update the chat height 
        //$this->dispatch('update-chat-height');
    }





    //retrive All message depend on convrstaion id from dataBAse
    public function loadMessages()
    {

        $count =  Message::where('conversation_id', $this->selectedConvrsation->id)->count();

        $this->loadedMessages = Message::where('conversation_id', $this->selectedConvrsation->id)
            ->skip($count - $this->paginate_var)
            ->take($this->paginate_var)
            ->get();

        return $this->loadedMessages;
    }




    public function mount()
    {
        $this->loadMessages();
    }


    //create message in database
    public function sendMessage()
    {


        $this->validate(['body' => 'required|string']);




        $CreateRecordMessage = Message::create([

            "conversation_id" => $this->selectedConvrsation->id,
            "sender_id" => auth()->user()->id,
            "receiver_id" => $this->selectedConvrsation->getReciver()->id,
            "body" => $this->body,
        ]);


        $this->reset("body");


        #scroll to bottom
        $this->dispatch('scroll-bottom');

        #push the message
        $this->loadedMessages->push($CreateRecordMessage);

        #update conversation model
        $this->selectedConvrsation->updated_at = now();
        $this->selectedConvrsation->save();

        #refresh chatlist
        $this->dispatch('chat.chat-list', 'refresh');


        #brodcats message 

        $this->selectedConvrsation->getReciver()
            ->notify(new MessageSent(
                auth()->user(),
                $CreateRecordMessage,
                $this->selectedConvrsation,
                $this->selectedConvrsation->getReciver()
            ));
    }

    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}
