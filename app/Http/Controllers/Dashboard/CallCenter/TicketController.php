<?php

namespace App\Http\Controllers\Dashboard\CallCenter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\Dashboard\CallCenter\TicketDataTable;
use App\Services\Dashboard\{CallCenter\TicketService};
use App\Models\{Callcenter, Ticket};
use Illuminate\Support\Facades\DB;

class TicketController extends Controller {
    public function __construct(protected TicketDataTable $dataTable, protected TicketService $ticketService)
    {
        $this->dataTable = $dataTable;
        $this->ticketService = $ticketService;
    }

    public function index()
    {
        $data = [
            'title' => 'Tickets',
        ];
        return $this->dataTable->render('dashboard.call-center.tickets.index', compact('data'));
    }

    public function store(Request $request)
    {
        try {
            $checkData = Ticket::where('order_code',$request->order_code)->first();
            if (!$checkData){
                $requestData = $request->all();
                $requestData = array_merge($requestData, ['callcenter_id' => get_user_data()->id]);
                //dd($requestData);
                $this->ticketService->create($requestData);
                return redirect()->route('CallCenterTickets.index')->with('success', 'Ticket created successfully');

            }
            return redirect()->route('CallCenterTickets.index')->with('error', 'An error occurred while creating the Ticket');

        } catch (\Exception $e) {
            return redirect()->route('CallCenterTickets.index')->with('error', 'An error occurred while creating the Ticket');
        }
    }


    public function show($id)
    {
        dd($id);
    }
}
