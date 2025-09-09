<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NaatiSupportTicket extends Model
{
    protected $fillable = ['user_id', 'title', 'status'];

    public function messages()
    {
        return $this->hasMany(NaatiTicketMessage::class, 'ticket_id');
    }
}
