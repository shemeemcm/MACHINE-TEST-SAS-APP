<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\OrganizationRepository;
use App\Repositories\UserRepository;
use App\Repositories\TicketRepository;

class DashboardController extends Controller
{
    protected $orgRepo;
    protected $userRepo;
    protected $ticketRepo;

    public function __construct(
        OrganizationRepository $orgRepo,
        UserRepository $userRepo,
        TicketRepository $ticketRepo
    ) {
        $this->orgRepo = $orgRepo;
        $this->userRepo = $userRepo;
        $this->ticketRepo = $ticketRepo;
    }
    public function index()
    {
        $totalOrganizations = $this->orgRepo->countAll();
        $totalUsers = $this->userRepo->countAll();
        $openTickets = $this->ticketRepo->countByStatus('open');
        $closedTickets = $this->ticketRepo->countByStatus('closed');
        $pendingTickets = $this->ticketRepo->countByStatuses(['in_progress', 'pending']);

        // Data for chart (tickets by status)
        $ticketStatusCounts = $this->ticketRepo->getStatusCounts();

        return view('admin.dashboard', compact(
            'totalOrganizations',
            'totalUsers',
            'openTickets',
            'closedTickets',
            'pendingTickets',
            'ticketStatusCounts'
        ));
    }
}
