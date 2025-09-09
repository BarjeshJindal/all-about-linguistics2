<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NaatiSupportTicket;
use App\Models\NaatiTicketMessage;
use App\Models\User;

class AdminTicketController extends Controller
{
    public function index()
    {
        $tickets = NaatiSupportTicket::with('messages')->latest()->get();

        foreach ($tickets as $ticket) {
            $user = \App\Models\User::where('id', $ticket->user_id)->first(['name', 'email']);
            $ticket->user_name = $user->name ?? 'Unknown';
            $ticket->user_email = $user->email ?? 'N/A';
        }

        return view('admin.tickets.ticket_list', compact('tickets'));

    }

    public function view($ticketId)
    {
        $ticket = NaatiSupportTicket::with('messages')->findOrFail($ticketId);
        $user = User::where('id', $ticket->user_id)->first(['name', 'email']);

        return view('admin.tickets.ticket_details', compact('ticket', 'user'));
    }

    public function reply(Request $request, $ticketId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        NaatiTicketMessage::create([
            'ticket_id' => $ticketId,
            'message' => $request->message,
            'sender_type' => 'admin',
        ]);

        return back()->with('success', 'Reply sent successfully.');
    }

    public function close($ticketId)
    {
        $ticket = NaatiSupportTicket::findOrFail($ticketId);
        $ticket->status = 'closed';
        $ticket->save();

        return back()->with('success', 'Ticket closed.');
    }

    public function reopen($ticketId)
    {
        $ticket = NaatiSupportTicket::findOrFail($ticketId);

        if ($ticket->status === 'closed') {
            $ticket->update(['status' => 'open']);
        }

        return back()->with('success', 'Ticket has been reopened.');
    }


}
