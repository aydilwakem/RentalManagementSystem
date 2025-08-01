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
        return Activity::query()
            ->when($this->search, function ($query) {
                $search = trim($this->search);
                $query->where('description', 'like', '%' . $search . '%')
                    ->orWhere('log_name', 'like', '%' . $search . '%')
                    ->orWhereHas('causer', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('subject_type', 'like', '%' . $search . '%');
            })
            ->when($this->logNameFilter, fn($q) => $q->where('log_name', $this->logNameFilter))
            ->when($this->roleFilter, function ($q) {
                $q->whereHas('causer', function ($query) {
                    $query->where('role', $this->roleFilter);
                });
            })
            ->orderBy($this->sortBy ?? 'created_at', $this->sortDir ?? 'desc')
            ->paginate($this->perPage ?? 10);
    }




    public function render()
    {
        $logs = Activity::with('causer') // Add this
            ->when(
                $this->search,
                fn($query) =>
                $query->where('description', 'like', '%' . $this->search . '%')
                    ->orWhere('log_name', 'like', '%' . $this->search . '%')
            )
            ->latest()
            ->paginate(perPage: 10);


        return view('livewire.admin.activity-logs.view-activity-logs', [
            'logs' => $logs,
        ]);
    }
}
