<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\TicketRepository;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    protected $ticketRepo;

    public function __construct(TicketRepository $ticketRepo)
    {
        $this->ticketRepo = $ticketRepo;
    }

    /**
     * Display a listing of tickets.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $priority = $request->query('priority');
        $assignee = $request->query('assignee');
        $perPage = $request->query('per_page', 15);
        $sortBy = $request->query('sort_by', 'id');
        $sortDir = $request->query('sort_dir', 'desc');

        $filters = [];
        if ($search) $filters['search'] = $search;
        if ($status) $filters['status'] = $status;
        if ($priority) $filters['priority'] = $priority;
        if ($assignee) $filters['assignee'] = $assignee;

        $tickets = $this->ticketRepo->getPaginated($filters, $perPage, $sortBy, $sortDir);
        $statuses = ['open', 'in_progress', 'closed'];
        $priorities = ['low', 'medium', 'high'];
        $users = User::orderBy('name')->pluck('name', 'id');

        return view('tickets.index', compact('tickets', 'search', 'status', 'priority', 'assignee', 'statuses', 'priorities', 'users'));
    }

    /** Show the form for creating a new ticket */
    public function create()
    {
        $statuses = ['open', 'in_progress', 'closed'];
        $priorities = ['low', 'medium', 'high'];
        $users = User::orderBy('name')->pluck('name', 'id');
        return view('tickets.create', compact('statuses', 'priorities', 'users'));
    }

    /** Store a newly created ticket */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:open,in_progress,closed',
            'priority' => 'required|in:low,medium,high',
            'assignee_id' => 'nullable|exists:users,id',
        ]);

        $ticket = $this->ticketRepo->create($validated);

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket created successfully.');
    }

    /** Display the specified ticket */
    public function show($id)
    {
        $ticket = $this->ticketRepo->getById($id);
        return view('tickets.show', compact('ticket'));
    }

    /** Show the form for editing the specified ticket */
    public function edit($id)
    {
        $ticket = $this->ticketRepo->getById($id);
        $statuses = ['open', 'in_progress', 'closed'];
        $priorities = ['low', 'medium', 'high'];
        $users = User::orderBy('name')->pluck('name', 'id');
        return view('tickets.edit', compact('ticket', 'statuses', 'priorities', 'users'));
    }

    /** Update the specified ticket */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:open,in_progress,closed',
            'priority' => 'required|in:low,medium,high',
            'assignee_id' => 'nullable|exists:users,id',
        ]);

        $this->ticketRepo->update($id, $validated);

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket updated successfully.');
    }

    /** Remove the specified ticket */
    public function destroy($id)
    {
        $this->ticketRepo->delete($id);
        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket deleted successfully.');
    }

    /** Assign a ticket to a user */
    public function assign(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        $this->ticketRepo->assign($id, $request->input('user_id'));
        return back()->with('success', 'Ticket assigned successfully.');
    }

    /** Reassign a ticket to a different user */
    public function reassign(Request $request, $id)
    {
        // Same validation as assign
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        $this->ticketRepo->reassign($id, $request->input('user_id'));
        return back()->with('success', 'Ticket reassigned successfully.');
    }

    /** Close the ticket */
    public function close($id)
    {
        $this->ticketRepo->close($id);
        return back()->with('success', 'Ticket closed successfully.');
    }
}
?>
