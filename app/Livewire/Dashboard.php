<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Customer;
use App\Models\JobOrder;
use App\Models\JobOrderMessage;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public $user;
    public $isAdmin = false;
    public $isTechnician = false;
    public $stats = [];
    public $recent_job_orders;
    public $recent_customers;

    public $sortBy = 'scheduled'; // Default sort by scheduled date

    #[Url(as: 'c')]
    public $showCompletedJobs = true; // Show completed jobs by default
    public $search = ''; // Search term for filtering job orders
    
    // Chat properties
    public $currentJobId = null;
    public $chatMessages = [];
    public $newMessage = '';
    public $showChatModal = false;
    public $chatTitle = 'Job Chat';

    // Job order management properties
    public $editingJobId = null;
    public $editingStatus = '';
    public $editingNotes = '';
    public $showStatusModal = false;
    public $showNotesModal = false;
    public $currentJob = null;

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
        // Get payment statistics
        $paymentService = app(PaymentService::class);
        $paymentStats = $paymentService->getDashboardStats();
        
        $this->stats = [
            'total_customers' => Customer::count(),
            'total_job_orders' => JobOrder::count(),
            'pending_job_orders' => JobOrder::where('status', 'pending_dispatch')->count(),
            'in_progress_job_orders' => JobOrder::where('status', 'in_progress')->count(),
            'completed_job_orders' => JobOrder::where('status', 'completed')->count(),
            // Add payment statistics
            'overdue_notices' => $paymentStats['overdue_notices'],
            'due_this_week' => $paymentStats['due_this_week'],
            'total_unpaid' => $paymentStats['total_unpaid'],
            'monthly_collected' => $paymentStats['monthly_collected'],
            'customers_with_unpaid' => $paymentStats['customers_with_unpaid'] ?? 0,
            'pending_notices' => $paymentStats['pending_notices'] ?? 0,
            'avg_monthly_collection' => $paymentStats['avg_monthly_collection'] ?? 0,
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
        } else {
            $this->stats = [
                'pending_jobs' => 0,
                'scheduled_jobs' => 0,
                'in_progress_jobs' => 0,
                'completed_today' => 0,
            ];
        }
    }

    private function getMyJobOrders()
    {
        if (!$this->isTechnician) {
            return collect();
        }

        $technician = $this->user->technician;
        
        if (!$technician) {
            return collect();
        }

        $assignedJobs = JobOrder::where('technician_id', $technician->id);

        // Get jobs scheduled for today OR late jobs (past scheduled date and not completed/cancelled) OR completed today
        $query = $assignedJobs->clone()
            ->with(['customer', 'technician.user'])
            ->where(function($mainQuery) {
                // Jobs scheduled for today (all statuses)
                $mainQuery->whereDate('scheduled_at', today())
                          // OR late jobs that are still active
                          ->orWhere(function($lateQuery) {
                              $lateQuery->where('scheduled_at', '<', now()->startOfDay())
                                       ->whereNotIn('status', ['completed', 'cancelled']);
                          });
                
                // Always include completed jobs from today if toggle is on
                if ($this->showCompletedJobs) {
                    $mainQuery->orWhere(function($completedQuery) {
                        $completedQuery->where('status', 'completed')
                                      ->whereDate('completed_at', today());
                    });
                }
            });

        // Apply search filter if search term is provided
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->whereHas('customer', function($customerQuery) use ($searchTerm) {
                $customerQuery->where(function($nameQuery) use ($searchTerm) {
                    $nameQuery->where('first_name', 'LIKE', $searchTerm)
                              ->orWhere('last_name', 'LIKE', $searchTerm)
                              ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$searchTerm])
                              ->orWhere('service_address', 'LIKE', $searchTerm);
                });
            });
        }

        // If toggle is off, exclude ALL completed jobs
        if (!$this->showCompletedJobs) {
            $query->where('status', '!=', 'completed');
        }

        // Apply sorting
        if ($this->sortBy === 'priority') {
            // Sort by priority: urgent, high, medium, low
            $query->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')");
        } else {
            // Default: Sort by late jobs first, then by scheduled date
            $query->orderByRaw("CASE 
                WHEN scheduled_at < ? THEN 0 
                ELSE 1 
            END ASC", [now()->startOfDay()])
            ->orderBy('scheduled_at', 'asc');
        }

        return $query->paginate(7);
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

    public function updatedSearch()
    {
        // Reset pagination when search changes
        $this->resetPage();
        
        // Dispatch a browser event to confirm the search is working
        $this->dispatch('searchUpdated', ['search' => $this->search]);
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
        $this->dispatch('searchCleared');
    }

    public function updatedSortBy()
    {
        // Reset pagination when sort changes
        $this->resetPage();
        
        // Dispatch a browser event to confirm the update is working
        $this->dispatch('sortUpdated', ['sortBy' => $this->sortBy]);
    }

    public function toggleCompletedJobs()
    {
        $this->showCompletedJobs = !$this->showCompletedJobs;
        
        // Reset pagination when toggle changes
        $this->resetPage();
        
        $this->dispatch('completedJobsToggled', ['showing' => $this->showCompletedJobs]);
    }

    // Job Management Methods for Technicians
    
    public function openStatusModal($jobId)
    {
        if (!$this->isTechnician) return;
        
        $this->currentJob = JobOrder::find($jobId);
        if (!$this->userCanAccessJobOrder($this->currentJob)) {
            session()->flash('error', 'Unauthorized access to this job order.');
            return;
        }
        
        $this->editingJobId = $jobId;
        $this->editingStatus = $this->currentJob->status;
        $this->showStatusModal = true;
    }

    public function updateJobStatus()
    {
        if (!$this->isTechnician || !$this->currentJob) return;

        $this->validate([
            'editingStatus' => 'required|in:en_route,in_progress,on_hold,completed',
        ]);

        try {
            $updates = ['status' => $this->editingStatus];
            
            // Update timestamps based on status
            if ($this->editingStatus === 'in_progress' && !$this->currentJob->started_at) {
                $updates['started_at'] = now();
            }
            
            if ($this->editingStatus === 'completed') {
                $updates['completed_at'] = now();
            }

            $result = $this->currentJob->update($updates);
            
            $this->closeStatusModal();
            // Data will refresh automatically on next render
            
            if ($this->editingStatus === 'completed') {
                if ($this->showCompletedJobs) {
                    session()->flash('success', 'Job #' . $this->currentJob->id . ' marked as completed successfully! You can see it in the completed jobs below.');
                } else {
                    session()->flash('success', 'Job #' . $this->currentJob->id . ' marked as completed successfully! Use the "Show Completed Jobs" button to view completed jobs.');
                }
            } else {
                session()->flash('success', 'Job status updated successfully!');
            }
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update job status: ' . $e->getMessage());
        }
    }

    public function closeStatusModal()
    {
        $this->showStatusModal = false;
        $this->editingJobId = null;
        $this->editingStatus = '';
        $this->currentJob = null;
    }

    public function openNotesModal($jobId)
    {
        if (!$this->isTechnician) return;
        
        $this->currentJob = JobOrder::find($jobId);
        if (!$this->userCanAccessJobOrder($this->currentJob)) {
            session()->flash('error', 'Unauthorized access to this job order.');
            return;
        }
        
        $this->editingJobId = $jobId;
        $this->editingNotes = $this->currentJob->resolution_notes ?? '';
        $this->showNotesModal = true;
    }

    public function updateJobNotes()
    {
        if (!$this->isTechnician || !$this->currentJob) return;

        $this->validate([
            'editingNotes' => 'nullable|string|max:1000',
        ]);

        try {
            $this->currentJob->update([
                'resolution_notes' => $this->editingNotes
            ]);
            
            $this->closeNotesModal();
            // Data will refresh automatically on next render
            
            session()->flash('success', 'Notes updated successfully!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update notes: ' . $e->getMessage());
        }
    }

    public function closeNotesModal()
    {
        $this->showNotesModal = false;
        $this->editingJobId = null;
        $this->editingNotes = '';
        $this->currentJob = null;
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'my_job_orders' => $this->getMyJobOrders(),
        ]);
    }
}
