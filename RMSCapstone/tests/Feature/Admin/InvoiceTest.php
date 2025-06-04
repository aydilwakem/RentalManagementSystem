<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Reservations\Invoice\InvoiceList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    //--------------------------- TEST TO SEE IF VISIBLE WITHOUT PERMISSION ---------------//
    //List View
    public function test_invoice_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('invoices-list');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.invoice-list'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(InvoiceList::class);
    }

    //------------------------ TESTS TO SEE IF ROUTES ARE VISIBLE WITH PERMISSION -----------------//
    //List View
    public function test_invoice_view_component_is_visible()
    {
        Permission::findOrCreate('invoices-list');

        $user = User::factory()->create();
        $user->givePermissionTo('invoices-list');

        $this->actingAs($user)
            ->get(route('admin.invoice-list'))
            ->assertSeeLivewire(InvoiceList::class);
    }
}
