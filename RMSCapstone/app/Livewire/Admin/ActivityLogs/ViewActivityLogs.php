<?php

namespace App\Livewire\Admin\ActivityLogs;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;
use Livewire\Attributes\Url;


class ViewActivityLogs extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $sortBy = 'created_at';
    public $sortDir = 'DESC';
    public $search = '';
    public $perPage = 10;

    public $logNameFilter = '';
    public $roleFilter = '';

    public function mount()
    {
        // Ensure it use a separate session key
        if (!session()->has('fake_ids_rooms')) {
            session(['fake_ids_rooms' => []]);
        }
    }

    public function setSortBy($sortByField)
    {
        if ($this->sortBy === $sortByField) {
            $this->sortDir = $this->sortDir == 'ASC' ? 'DESC' : 'ASC';
            return;
        }

        $this->sortBy = $sortByField;
        $this->sortDir = 'ASC';
    }

    public function placeholder()
    {
        return view('livewire.admin.placeholder');
    }

    public function getActivityLogsProperty()
    {
        $search = trim(preg_replace('/\s+/', ' ', $this->search)); // Normalize spaces

        return Activity::query()
            ->when($this->search, function ($query) use ($search) {
                $query->where('description', 'like', '%' . $search . '%')
                    ->orWhere('log_name', 'like', '%' . $search . '%')
                    ->orWhere('subject_type', 'like', '%' . $search . '%')
                    ->orWhereHas('causer', function ($q) use ($search) {
                        $q->whereRaw("CONCAT_WS(' ', name, middle_name, last_name, suffix) LIKE ?", ["%{$search}%"])
                            ->orWhere('name', 'like', '%' . $search . '%')
                            ->orWhere('middle_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%')
                            ->orWhere('suffix', 'like', '%' . $search . '%');
                    });
            })
            ->when($this->logNameFilter, fn($q) => $q->where('log_name', $this->logNameFilter))
            ->when($this->roleFilter, function ($q) {
                $q->whereHas('causer', function ($query) {
                    $query->where('role', $this->roleFilter);
                });
            })
            ->with('causer')
            ->orderBy($this->sortBy ?? 'created_at', $this->sortDir ?? 'desc')
            ->paginate($this->perPage ?? 10);
    }

    public function render()
    {
        // Normalize search input by removing extra spaces
        $search = trim(preg_replace('/\s+/', ' ', $this->search));

        // Fetch activity logs with filters and pagination
        $logs = Activity::with('causer') // Eager load the user who performed the activity
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    // Search in the activity log fields
                    $q->where('description', 'like', '%' . $search . '%')
                        ->orWhere('log_name', 'like', '%' . $search . '%')
                        ->orWhere('subject_type', 'like', '%' . $search . '%')

                        // Search in the related user (causer) fields
                        ->orWhereHas('causer', function ($causerQuery) use ($search) {
                            // Use lower-case search to be case-insensitive across all DBs
                            $causerQuery->whereRaw("
                          LOWER(CONCAT_WS(' ', name, middle_name, last_name, suffix)) 
                          LIKE LOWER(?)
                      ", ["%" . $search . "%"])
                                // Also search each part separately in case user types only first or last name
                                ->orWhereRaw('LOWER(name) LIKE LOWER(?)', ["%{$search}%"])
                                ->orWhereRaw('LOWER(middle_name) LIKE LOWER(?)', ["%{$search}%"])
                                ->orWhereRaw('LOWER(last_name) LIKE LOWER(?)', ["%{$search}%"])
                                ->orWhereRaw('LOWER(suffix) LIKE LOWER(?)', ["%{$search}%"]);
                        });
                });
            })
            ->when($this->logNameFilter, fn($q) => $q->where('log_name', $this->logNameFilter))

            ->when($this->roleFilter, function ($q) {
                $q->whereHas('causer', function ($query) {
                    $query->where('role', $this->roleFilter);
                });
            })

            ->orderBy($this->sortBy ?? 'created_at', $this->sortDir ?? 'desc')
            ->paginate($this->perPage ?? 10);

        // Return view with logs data
        return view('livewire.admin.activity-logs.view-activity-logs', [
            'logs' => $logs,
        ]);
    }
}
