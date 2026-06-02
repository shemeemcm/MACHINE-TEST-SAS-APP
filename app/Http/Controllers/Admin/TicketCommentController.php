<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\TicketCommentRepository;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketCommentController extends Controller
{
    protected $commentRepo;

    public function __construct(TicketCommentRepository $commentRepo)
    {
        $this->commentRepo = $commentRepo;
    }

    /**
     * Display comments for a ticket.
     */
    public function index($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $comments = $this->commentRepo->getAllByTicket($ticketId);
        return view('tickets.comments.index', compact('ticket', 'comments'));
    }

    /** Show form to create a comment */
    public function create($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        return view('tickets.comments.create', compact('ticket'));
    }

    /** Store new comment */
    public function store(Request $request, $ticketId)
    {
        $validated = $request->validate([
            'comment' => 'required|string',
        ]);
        $validated['ticket_id'] = $ticketId;
        $validated['user_id'] = auth()->id();
        $this->commentRepo->create($validated);
        return redirect()->route('admin.tickets.show', $ticketId)
            ->with('success', 'Comment added.');
    }

    /** Show edit form */
    public function edit($ticketId, $commentId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $comment = $this->commentRepo->findById($commentId);
        return view('tickets.comments.edit', compact('ticket', 'comment'));
    }

    /** Update comment */
    public function update(Request $request, $ticketId, $commentId)
    {
        $validated = $request->validate([
            'comment' => 'required|string',
        ]);
        $this->commentRepo->update($commentId, $validated);
        return redirect()->route('admin.tickets.show', $ticketId)
            ->with('success', 'Comment updated.');
    }

    /** Delete comment */
    public function destroy($ticketId, $commentId)
    {
        $this->commentRepo->delete($commentId);
        return redirect()->route('admin.tickets.show', $ticketId)
            ->with('success', 'Comment deleted.');
    }
}
?>
