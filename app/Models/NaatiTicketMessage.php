<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NaatiTicketMessage extends Model
{
    protected $fillable = ['ticket_id', 'user_id', 'message', 'sender_type'];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class);
    }
}
