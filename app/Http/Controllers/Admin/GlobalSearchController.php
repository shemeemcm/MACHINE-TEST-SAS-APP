<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\OrganizationRepository;
use App\Repositories\UserRepository;
use App\Repositories\TicketRepository;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    protected $orgRepo;
    protected $userRepo;
    protected $ticketRepo;

    public function __construct(
        OrganizationRepository $orgRepo,
        UserRepository $userRepo,
        TicketRepository $ticketRepo
    ) {
        $this->orgRepo   = $orgRepo;
        $this->userRepo  = $userRepo;
        $this->ticketRepo = $ticketRepo;
    }

    /**
     * Display global search results grouped by entity.
     */
    public function __invoke(Request $request)
    {
        $search = $request->query('q');
        $results = [];
        if ($search) {
            // Organizations
            $orgFilters = ['search' => $search];
            $results['organizations'] = $this->orgRepo->getPaginated($orgFilters, 5);

            // Users
            $userFilters = ['search' => $search];
            $results['users'] = $this->userRepo->getAll($userFilters, 5);

            // Tickets
            $ticketFilters = ['search' => $search];
            $results['tickets'] = $this->ticketRepo->getPaginated($ticketFilters, 5);
        }
        return view('admin.global-search', compact('results', 'search'));
    }
}
?>
