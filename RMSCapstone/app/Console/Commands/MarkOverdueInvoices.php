<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use Carbon\Carbon;

class MarkOverdueInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:mark-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark invoices as overdue if the due date has passed and still pending';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 🔹 Mark invoices as overdue if due_date <= now and status is pending
        $overdueInvoices = Invoice::where('invoice_status', 'pending')
            ->where('due_date', '<=', Carbon::now())
            ->update(['invoice_status' => 'overdue']);

        $this->info("Overdue invoices updated: $overdueInvoices");
    }
}
