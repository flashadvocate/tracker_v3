<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketComment;
use App\Notifications\AdminTicketUpdated;
use Illuminate\Http\Request;

class TicketCommentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $this->authorize('createComment', $ticket);

        $validated = $request->validate([
            'comment' => 'string|min:5|required'
        ]);

        $comment = $ticket->comments()->create([
            'body' => $validated['comment'],
            'user_id' => auth()->id(),
        ]);

        if ($comment->user_id != $ticket->caller_id) {
            $author = $comment->user->name;
            $disclaimer = "*Note: Use the ticket link to respond to this comment. You cannot reply directly via discord.*";
            $ticket->notify(new AdminTicketUpdated("```{$comment->body} -{$author}```{$disclaimer}"));
        }

        return redirect(route('help.tickets.show', $ticket));
    }

    public function delete(Ticket $ticket, TicketComment $comment)
    {
        $this->authorize('deleteComment', $comment);

        $comment->delete();

        $this->showToast('Comment successfully deleted');

        return redirect(route('help.tickets.show', $ticket));
    }
}
