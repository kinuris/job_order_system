<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Customer;
use App\Models\JobOrder;
use App\Models\JobOrderMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class Dashboard extends Component
{
    public $user;
    public $isAdmin = false;
    public $isTechnician = false;
    public $stats = [];
    public $recent_job_orders;
    public $recent_customers;
    public $my_job_orders;
    
    // Chat properties
    public $currentJobId = null;
    public $chatMessages = [];
    public $newMessage = '';
    public $showChatModal = false;
    public $chatTitle = 'Job Chat';

    public function mount()
    {
        $this->user = Auth::user();
        $this->isAdmin = $this->user->role === 'admin';
        $this->isTechnician = $this->user->role === 'technician';
        
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        if ($this->isAdmin) {
            $this->loadAdminData();
        } elseif ($this->isTechnician) {
            $this->loadTechnicianData();
        }
    }

    private function loadAdminData()
    {
        $this->stats = [
            'total_customers' => Customer::count(),
            'total_job_orders' => JobOrder::count(),
            'pending_job_orders' => JobOrder::where('status', 'pending_dispatch')->count(),
            'in_progress_job_orders' => JobOrder::where('status', 'in_progress')->count(),
        ];

        $this->recent_job_orders = JobOrder::with(['customer', 'technician.user'])
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $this->recent_customers = Customer::with('jobOrders')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    private function loadTechnicianData()
    {
        $technician = $this->user->technician;
        
        if ($technician) {
            $assignedJobs = JobOrder::where('technician_id', $technician->id);
            
            $this->stats = [
                'pending_jobs' => $assignedJobs->clone()->where('status', 'pending_dispatch')->count(),
                'scheduled_jobs' => $assignedJobs->clone()->where('status', 'scheduled')->count(),
                'in_progress_jobs' => $assignedJobs->clone()->where('status', 'in_progress')->count(),
                'completed_today' => $assignedJobs->clone()->where('status', 'completed')->whereDate('completed_at', today())->count(),
            ];

            // Get jobs scheduled for today OR late jobs (past scheduled date and not completed/cancelled)
            $this->my_job_orders = $assignedJobs->clone()
                ->with(['customer', 'technician.user'])
                ->where(function($query) {
                    $query->whereDate('scheduled_at', today())
                          ->orWhere(function($subQuery) {
                              $subQuery->where('scheduled_at', '<', now()->startOfDay())
                                       ->whereNotIn('status', ['completed', 'cancelled']);
                          });
                })
                ->whereIn('status', ['pending_dispatch', 'scheduled', 'in_progress', 'on_hold'])
                ->orderByRaw("CASE 
                    WHEN scheduled_at < ? THEN 0 
                    ELSE 1 
                END ASC", [now()->startOfDay()])
                ->orderBy('scheduled_at', 'asc')
                ->get();
        } else {
            $this->stats = [
                'pending_jobs' => 0,
                'scheduled_jobs' => 0,
                'in_progress_jobs' => 0,
                'completed_today' => 0,
            ];
            $this->my_job_orders = collect();
        }
    }

    public function openChatModal($jobId)
    {
        $this->currentJobId = $jobId;
        $this->chatTitle = "Job #{$jobId} Chat";
        $this->showChatModal = true;
        $this->loadChatMessages();
        $this->markMessagesAsRead();
        
        // Dispatch event for JavaScript to start polling
        $this->dispatch('chat-modal-opened');
    }

    public function closeChatModal()
    {
        $this->showChatModal = false;
        $this->currentJobId = null;
        $this->chatMessages = [];
        $this->newMessage = '';
        
        // Dispatch event for JavaScript to stop polling
        $this->dispatch('chat-modal-closed');
    }

    public function loadChatMessages()
    {
        if (!$this->currentJobId) return;

        $jobOrder = JobOrder::find($this->currentJobId);
        
        // Check authorization
        if (!$this->userCanAccessJobOrder($jobOrder)) {
            $this->addError('chat', 'Unauthorized access to this job order.');
            return;
        }

        $messages = $jobOrder->messages()
            ->with('user:id,name,role')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'user_name' => $message->user->name,
                    'user_role' => $message->user->role,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at->format('M j, Y g:i A'),
                    'is_own_message' => $message->user_id === Auth::id(),
                ];
            });

        $this->chatMessages = $messages->toArray();
    }

    public function sendMessage()
    {
        $this->validate([
            'newMessage' => 'required|string|max:1000',
        ]);

        if (!$this->currentJobId) return;

        $jobOrder = JobOrder::find($this->currentJobId);
        
        // Check authorization
        if (!$this->userCanAccessJobOrder($jobOrder)) {
            $this->addError('chat', 'Unauthorized access to this job order.');
            return;
        }

        $message = JobOrderMessage::create([
            'job_order_id' => $this->currentJobId,
            'user_id' => Auth::id(),
            'message' => trim($this->newMessage),
            'is_read' => false,
        ]);

        $this->newMessage = '';
        $this->loadChatMessages();
        
        // Dispatch browser event to scroll to bottom
        $this->dispatch('chat-message-sent');
    }

    public function markMessagesAsRead()
    {
        if (!$this->currentJobId) return;

        JobOrderMessage::where('job_order_id', $this->currentJobId)
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    #[On('refresh-chat')]
    public function refreshChat()
    {
        if ($this->showChatModal && $this->currentJobId) {
            $this->loadChatMessages();
            $this->markMessagesAsRead();
        }
    }

    private function userCanAccessJobOrder($jobOrder)
    {
        if (!$jobOrder) return false;
        
        $user = Auth::user();
        
        // Admin can access all job orders
        if ($user->role === 'admin') {
            return true;
        }
        
        // Technician can only access job orders assigned to them
        if ($user->role === 'technician') {
            $technician = $user->technician;
            if ($technician) {
                return $jobOrder->technician_id === $technician->id;
            }
        }
        
        return false;
    }

    public function getUnreadMessageCount($jobId)
    {
        $jobOrder = JobOrder::find($jobId);
        if (!$this->userCanAccessJobOrder($jobOrder)) {
            return 0;
        }

        return $jobOrder->messages()
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->count();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
