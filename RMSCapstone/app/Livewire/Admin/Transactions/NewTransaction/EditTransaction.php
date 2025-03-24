<?php

namespace App\Livewire\Admin\Transactions\NewTransaction;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class EditTransaction extends Component
{
    public function render()
    {
        return view('livewire.admin.transactions.new-transaction.edit-transaction');
    }
}
