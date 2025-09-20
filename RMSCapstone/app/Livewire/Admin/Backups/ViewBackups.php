<?php

namespace App\Livewire\Admin\Backups;

use App\Models\Backup;
use App\Services\BackupService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;


#[Layout('layouts.app')]
class ViewBackups extends Component
{
    //---------------- Declarations ----------- //
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url()]
    public $perPage = 10;

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    //------------------ Modals ---------------- //
    public $confirmItemDelete = false;
    public $confirmBulkDelete = false;
    public $selectedItemId = null;

    //public declaration for bulk actions
    public $selectedRows = [];
    public $selectPageRows = false;

    public function placeholder(){
        return view('livewire.admin.placeholder');
    }

    public function updatedSelectPageRows($value){
        if ($value){
            $this->selectedRows = $this->backups->pluck('id')->map(function ($id){
                return (string) $id;
            })->toArray();
        } else {
            $this->reset(['selectedRows', 'selectPageRows']);
        }
    }

    public function getBackupsProperty(){
        return Backup::query()
            ->where('name', 'like', '%' . trim($this->search) . '%')
            ->orWhere('status', 'like', '%' . trim($this->search) . '%')
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }

    public function deleteSelectedRows(){
        try {
            $backups = Backup::whereIn('id', $this->selectedRows)->get();
            $backupService = new BackupService();

            foreach ($backups as $backup) {
                $backupService->deleteBackup($backup);
            }

            $this->confirmBulkDelete = false;
            $this->reset(['selectedRows', 'selectPageRows']);
            session()->flash('message', 'All selected backups have been deleted!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete backups: ' . $e->getMessage());
        }
    }

    public function confirmDeleteInBulk(){
        $this->confirmBulkDelete = true;
    }

    public function confirmDelete($id)
    {
        $this->selectedItemId = $id;
        $this->confirmItemDelete = true;
    }

    public function downloadBackup($id)
    {
        try {
            $backup = Backup::findOrFail($id);
            $backupService = new BackupService();
            
            return $backupService->downloadBackup($backup);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to download backup: ' . $e->getMessage());
        }
    }

    public function cleanupBackups($days = 30)
    {
        try {
            $backupService = new BackupService();
            $deletedCount = $backupService->cleanupOldBackups($days);
            
            session()->flash('message', "Successfully cleaned up {$deletedCount} old backup(s).");
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to cleanup backups: ' . $e->getMessage());
        }
    }

    public function deleteBackup()
    {
        try {
            $backup = Backup::find($this->selectedItemId);
            
            if ($backup) {
                $backupService = new BackupService();
                $backupService->deleteBackup($backup);
                
                $this->confirmItemDelete = false;
                $this->selectedItemId = null;
                
                session()->flash('message', 'Backup successfully deleted!');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete backup: ' . $e->getMessage());
        }
    }

    /**
     * Sets the sorting criteria for displaying backups.
     */
    public function setSortBy($sortByField)
    {
        if ($this->sortBy == $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = "DESC";
    }

    protected function formatSize($bytes): string
    {
        if ($bytes === null) return 'N/A';
        
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    public function render()
    {
        $backups = $this->backups;

        // Format sizes for display
        $backups->getCollection()->transform(function ($backup) {
            $backup->formatted_size = $this->formatSize($backup->size);
            return $backup;
        });

        return view('livewire.admin.backups.view-backups', [
            'backups' => $backups,
        ]);
    }
}