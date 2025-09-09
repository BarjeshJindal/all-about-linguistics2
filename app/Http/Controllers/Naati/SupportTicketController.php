<?php

namespace App\Http\Controllers\Naati;

use Illuminate\Http\Request;
use App\Models\NaatiSupportTicket;
use App\Models\NaatiTicketMessage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller; 


class SupportTicketController extends Controller
{
    // Show the ticket creation form
    public function showCreateForm()
    {

        return view('naati.users.tickets.create_ticket');
    }

    // List all tickets for the logged-in user
    public function list()
    {
        $userId = Auth::id();

        $tickets = NaatiSupportTicket::where('user_id', $userId)->latest()->get();
        return view('naati.users.tickets.tickets-list', compact('tickets'));
    }

    // Show the conversation for a specific ticket
    public function details($ticketId)
    {
        $userId = Auth::id();

        $ticket = NaatiSupportTicket::with('messages')
            ->where('id', $ticketId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $user = User::where('id', $ticket->user_id)->first(['name', 'email']);

        // dd($ticket);

        return view('naati.users.tickets.ticket_details', compact('ticket', 'user'));
    }

    // Create a new support ticket and the first message
    public function create(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $ticket = NaatiSupportTicket::create([
            'user_id' => $userId,
            'title' => $request->title,
            'status' => 'open',
        ]);

        NaatiTicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $userId,
            'message' => $request->message,
            'sender_type' => 'user',
        ]);

         return redirect()->back()->with('success','Ticket created');
    }

    // Add a message to an existing ticket
    public function sendMessage(Request $request, $ticketId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $userId = Auth::id();

        $ticket = NaatiSupportTicket::where('id', $ticketId)
            ->where('user_id', $userId)
            ->firstOrFail();

        NaatiTicketMessage::create([
            'ticket_id' => $ticketId,
            'user_id' => $userId,
            'message' => $request->message,
            'sender_type' => 'user',
        ]);

        return redirect()->back()->with('success','Message Sent');
    }

    public function reopen($ticketId)
    {
        $ticket = NaatiSupportTicket::where('id', $ticketId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($ticket->status === 'closed') {
            $ticket->update(['status' => 'open']);
        }

        return back()->with('success', 'Ticket has been reopened.');
    }


}
