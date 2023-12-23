<?php

namespace App\Http\Controllers\Dashboard\CallCenter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\Dashboard\CallCenter\TicketDataTable;
use App\Services\Dashboard\{CallCenter\TicketService};
use App\Models\{Callcenter, ReplyTicket, Ticket};
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
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
            $checkData = Ticket::where('order_code', $request->order_code)->first();
            if (!$checkData) {
                $requestData = $request->all();
                $requestData = array_merge($requestData, ['callcenter_id' => get_user_data()->id]);
                $data = $this->ticketService->create($requestData);
                ReplyTicket::create([
                    'ticket_id' => $data->id,
                    'callcenter_id' => $data->callcenter_id,
                    'status' => 'waiting',
                    'messages' => $data->subject,
                ]);
                return redirect()->route('CallCenterTickets.index')->with('success', 'Ticket created successfully');
            }
            return redirect()->route('CallCenterTickets.index')->with('error', 'An error occurred while creating the Ticket');

        } catch (\Exception $e) {
            return redirect()->route('CallCenterTickets.index')->with('error', 'An error occurred while creating the Ticket');
        }
    }

    public function show($id) {
        $ticket = Ticket::where('ticket_code', $id)->first();
        if ($ticket) {
            $replies = ReplyTicket::where('ticket_id', $ticket->id)->get();
            $data = [
                'ticket' => $ticket,
                'replies' => $replies,
            ];
            return view('dashboard.call-center.tickets.ticket_details', compact('data'));
        }
    }

    public function addReply(Request $request, $ticketId) {
        $request->validate([
            'message' => 'required',
        ]);
        $latestReply = ReplyTicket::where('ticket_id', $ticketId)->latest()->first();
        if ($latestReply) {
            if (auth('admin')->check()) {
                $latestReply->update([
                    'messages' => $request->input('message'),
                    'status' => 'read',
                    'admin_id' => auth('admin')->user()->id,
                ]);
            } elseif (auth('call-center')->check() && get_user_data()->type == 'manager') {
                $latestReply->update([
                    'messages' => $request->input('message'),
                    'status' => 'read',
                    'callcenter_id' => $latestReply->ticket->callcenter_id,
                    'manager_id' => auth('call-center')->user()->id,
                ]);
                $latestReply->ticket->assign_to_callcenter = get_user_data()->id;
                $latestReply->ticket->save();
            }
        } else {
            $reply = new ReplyTicket();
            $reply->ticket_id = $ticketId;
            if (auth('admin')->check()) {
                $reply->admin_id = auth('admin')->user()->id;
            } elseif (auth('call-center')->check()) {
                $reply->callcenter_id = auth('call-center')->user()->id;
            }
            $reply->messages = $request->input('message');
            $reply->status = 'waiting';
            $reply->save();
        }
        $ticket = Ticket::whereId($ticketId)->first();
        return redirect()->route('CallCenterTickets.show', $ticket->ticket_code);
    }
}