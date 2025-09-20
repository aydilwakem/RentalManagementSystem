<?php

namespace App\Livewire\Admin\Backups;

use Livewire\Component;
use App\Services\BackupService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;


#[Layout('layouts.app')]
class CreateBackup extends Component
{
    public $dbSize;
    public $isCreating = false;

    public function mount()
    {
        // Get database size estimation
        $database = config('database.connections.mysql.database');
        $dbSize = 0;
        
        try {
            $result = DB::select("
                SELECT SUM(data_length + index_length) / 1024 AS size
                FROM information_schema.TABLES 
                WHERE table_schema = ?
                GROUP BY table_schema
            ", [$database]);
            
            $this->dbSize = $result[0]->size ?? 0;
        } catch (\Exception $e) {
            $this->dbSize = 'unknown (estimation failed)';
        }
    }

    public function createBackup()
    {
        $this->isCreating = true;
        
        try {
            $backupService = new BackupService();
            $backup = $backupService->createBackup();
            
            session()->flash('message', 'Backup created successfully: ' . $backup->name);
            $this->redirect(route('admin.view-backups'));
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create backup: ' . $e->getMessage());
            $this->isCreating = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.backups.create-backup');
    }
}