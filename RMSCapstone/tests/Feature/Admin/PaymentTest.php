<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Reservations\Payments\PaymentList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    //-------------------- TEST TO SEE IF PAYMENTS IS VISIBLE WITHOUT PERMISSION ----------//
    //List View
    public function test_payments_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('payments-list');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.payments-list'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(PaymentList::class);
    }

    //List View
    public function test_payments_view_component_is_visible()
    {
        Permission::findOrCreate('payments-list');

        $user = User::factory()->create();
        $user->givePermissionTo('payments-list');

        $this->actingAs($user)
            ->get(route('admin.payments-list'))
            ->assertSeeLivewire(PaymentList::class);
    }
}
