<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'receiver_id',
        'read_at',
        'receiver_deleted_at',
        'sender_deleted_at',
        'body',
    ];


    public function conversation(){
        return $this->belongsTo(Conversation::class);
    }

    public function isReadMessage(){
        return ($this->read_at != null);
    }
}
