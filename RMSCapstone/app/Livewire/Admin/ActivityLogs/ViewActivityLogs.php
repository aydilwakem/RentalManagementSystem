<?php

namespace App\Livewire\Admin\ActivityLogs;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;
use Livewire\Attributes\Url;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class ViewActivityLogs extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $sortBy = 'created_at';
    public $sortDir = 'DESC';
    public $search = '';
    public $perPage = 10;



    public $start_date;
    public $end_date;
    public $filterApplied = false;
    public $filteredAuditLogs = [];

    public $audit_log_name_filter = '';
    public $audit_event_filter = '';
    public $eventTypes = [];
    public $logNames = [];

    public function render()
    {
        return view('livewire.admin.activity-logs.view-activity-logs', [
            'logs' => $this->logs,
        ]);
    }

    public function mount()
    {
        $this->eventTypes = Activity::select('event')
            ->distinct()
            ->orderBy('event')
            ->pluck('event')
            ->toArray();

        $this->logNames = Activity::select('log_name')
            ->distinct()
            ->orderBy('log_name')
            ->pluck('log_name')
            ->toArray();

        //Default date range current month
        $now = Carbon::now('Asia/Manila');
        $this->start_date = $now->copy()->startOfMonth()->format(('Y-m-d'));
        $this->end_date = $now->copy()->endOfMonth()->format(('Y-m-d'));
    }

    public function apply_audit_log_filter()
    {
        $query = Activity::query();

        if ($this->start_date) {
            $query->whereDate('created_at', '>=', Carbon::parse($this->start_date));
        }

        if ($this->end_date) {
            $query->whereDate('created_at', '<=', Carbon::parse($this->end_date));
        }

        if ($this->audit_log_name_filter) {
            $query->where('log_name', $this->audit_log_name_filter);
        }

        if ($this->audit_event_filter) {
            $query->where('event', $this->audit_event_filter);
        }

        $this->filteredAuditLogs = $query
            ->orderBy($this->sortBy, $this->sortDir)
            ->get();

        $this->filterApplied = true;
    }

    public function getLogsProperty()
    {
        if (!$this->filterApplied) {
            return Activity::query()
                ->with('causer')
                ->orderBy($this->sortBy ?? 'created_at', $this->sortDir ?? 'desc')
                ->paginate($this->perPage ?? 10);
        }

        $search = trim(preg_replace('/\s+/', ' ', $this->search));

        return Activity::query()
            ->when($this->start_date, function ($query) {
                $query->whereDate('created_at', '>=', Carbon::parse($this->start_date));
            })
            ->when($this->end_date, function ($query) {
                $query->whereDate('created_at', '<=', Carbon::parse($this->end_date));
            })
            ->when($this->audit_event_filter, function ($query) {
                $query->where('event', $this->audit_event_filter);
            })
            ->when($this->audit_log_name_filter, function ($query) {
                $query->where('log_name', $this->audit_log_name_filter);
            })
            ->when($this->search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
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
                });
            })
            ->with('causer')
            ->orderBy($this->sortBy ?? 'created_at', $this->sortDir ?? 'desc')
            ->paginate($this->perPage ?? 10);
    }

    /**
     * This method resets the filters applied to the activity logs.
     * It resets the start_date, end_date, audit_log_name_filter, audit_event_filter,
     * filterApplied flag, and filteredAuditLogs array.
     * @return void
     */
    public function resetFilters()
    {
        $this->reset(['audit_log_name_filter', 'audit_event_filter', 'filterApplied']);
        $this->filteredAuditLogs = [];

        //Default date range current month
        $now = Carbon::now('Asia/Manila');
        $this->start_date = $now->copy()->startOfMonth()->format(('Y-m-d'));
        $this->end_date = $now->copy()->endOfMonth()->format(('Y-m-d'));
    }

    /**
     * This method is called when the search property is updated.
     * It resets the pagination to the first page.
     * @return void
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * This method exports the activity logs to a PDF file.
     * It checks if there are logs to export, loads the view with the logs data,
     * and streams the PDF download.
     */
    public function exportLogsToPDF()
    {
        $logs = $this->getLogsProperty();

        if ($logs->isEmpty()) {
            session()->flash('error', 'No logs found to export.');
            return;
        }

        // Define variables safely
        $start_date = $this->start_date ?? now();
        $end_date = $this->end_date ?? now();
        $logNameFilter = $this->audit_log_name_filter ?? null;
        $eventStatusFilter = $this->audit_event_filter ?? null;

        // Pass all required data to the PDF view
        $pdf = Pdf::loadView('livewire.admin.reports.audit-logs', compact(
            'logs',
            'start_date',
            'end_date',
            'logNameFilter',
            'eventStatusFilter'
        ));

        $filename = 'Audit-Logs-Summary-' . Carbon::parse($start_date)->format('Ymd') . '-' . Carbon::parse($end_date)->format('Ymd') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $filename);
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
}
