<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Settings\Payments\CreatePayment;
use App\Livewire\Admin\Settings\Payments\DeletedPayments;
use App\Livewire\Admin\Settings\Payments\EditPayment;
use App\Livewire\Admin\Settings\Payments\ViewPayment;
use App\Livewire\Admin\Settings\Payments\ViewPayments;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\TransactionUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    //TESTS TO SEE IF ROUTES ARE VISIBLE WITH PERMISSION
    //List View
    public function test_payment_method_view_component_is_visible()
    {
        Permission::findOrCreate('payment-method-list');

        $user = User::factory()->create();
        $user->givePermissionTo('payment-method-list');

        $this->actingAs($user)
            ->get(route('admin.payments'))
            ->assertSeeLivewire(ViewPayments::class);
    }

    //Individual View
    public function test_payment_method_single_view_component_is_visible()
    {
        Permission::findOrCreate('payment-method-view');

        $user = User::factory()->create();
        $user->givePermissionTo('payment-method-view');

        $paymentMethod = PaymentMethod::factory()->create(); 

        $this->actingAs($user)
           ->get(route('admin.view-payment', ['paymentMethod' => $paymentMethod->id]))
            ->assertSeeLivewire(ViewPayment::class);
    }

    //Create View
    public function test_payment_method_create_component_is_visible()
    {
        Permission::findOrCreate('payment-method-create');

        $user = User::factory()->create();
        $user->givePermissionTo('payment-method-create');

        $this->actingAs($user)
            ->get(route('admin.create-payment'))
            ->assertSeeLivewire(CreatePayment::class);
    }

    //Edit View
    public function test_payment_method_edit_component_is_visible()
    {
       Permission::findOrCreate('payment-method-edit');

       $user = User::factory()->create();
       $user->givePermissionTo('payment-method-edit');

        $paymentMethod = PaymentMethod::factory()->create();

        // Act as the user and visit the edit route 
        $this->actingAs($user)
        ->get(route('admin.edit-payment', ['paymentMethod' => $paymentMethod->id]))
        ->assertSeeLivewire(EditPayment::class);
    }

    //Soft Delete View
    public function test_payment_method_soft_delete_component_is_visible()
    {
        Permission::findOrCreate('payment-method-soft-delete');

        $user = User::factory()->create();
        $user->givePermissionTo('payment-method-soft-delete');

        $this->actingAs($user)
            ->get(route('admin.deleted-payments'))
            ->assertSeeLivewire(DeletedPayments::class);
    }

    //TESTS TO SEE IF ROUTES ARE VISIBLE WITHOUT PERMISSION
    //List View
    public function test_payment_method_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('payment-method-list');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.payments'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ViewPayments::class);
    }

    //Single View
    public function test_payment_method_single_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('payment-method-view');

        //user without permission
        $user = User::factory()->create();

        $paymentMethod = PaymentMethod::factory()->create();

        $response = $this->actingAs($user)
        ->get(route('admin.view-payment', $paymentMethod->id));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ViewPayment::class);
    }

    //Create View
    public function test_payment_method_create_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('payment-method-create');

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.create-payment'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(CreatePayment::class);
    }

    //Edit View
    public function test_payment_method_edit_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('payment-method-edit');

        $paymentMethod = PaymentMethod::factory()->create();
        $user = User::factory()->create();

         $response = $this->actingAs($user)
        ->get(route('admin.edit-payment', $paymentMethod->id));

        $response->assertForbidden();
    }

    //Soft Delete View
    public function test_payment_method_soft_delete_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('payment-method-soft-delete');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.deleted-payments'));

        // Assert forbidden or redirect
        $response->assertForbidden(); 

        //Ensure the Livewire component is NOT rendered
        $response->assertDontSeeLivewire(DeletedPayments::class);
    }


    // -------------------------- CRUD TESTING
    //Create - with no image upload
    public function test_payment_method_can_be_created()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('payment-method-create'); 

        $paymentMethodData = PaymentMethod::factory()->make()->toArray(); 

        $livewire = Livewire::actingAs($user)
            ->test(CreatePayment::class);

        foreach ($paymentMethodData as $key => $value) {
            $livewire->set($key, $value);
        }

        $livewire
            ->call('savePaymentMethod');

        $this->assertDatabaseHas('pm_payment_methods', [
            'mode_of_payment_name' => $paymentMethodData['mode_of_payment_name'],
        ]);
    }

   //Edit - with no imageupload
    public function test_payment_method_can_be_updated()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('payment-method-edit');
    
        $paymentMethod = PaymentMethod::factory()->create();
    
        // Create a set of new (fake) updated data
        $updatedData = PaymentMethod::factory()->make()->toArray();
    
        $livewire = Livewire::actingAs($user)
            ->test(EditPayment::class, ['paymentMethod' => $paymentMethod]);
    
        foreach ($updatedData as $key => $value) {
            $livewire->set($key, $value);
        }
    
        $livewire
            ->call('updatePaymentMethod') 
            ->assertSessionHas('message', 'Payment Method successfully updated!')
            ->assertRedirect(route('admin.payments'));
    
        $this->assertDatabaseHas('pm_payment_methods', [
            'id' => $paymentMethod->id,
            'mode_of_payment_name' => $updatedData['mode_of_payment_name'],
        ]);
    }

    //Soft Delete
    public function test_payment_method_can_be_soft_deleted()
    {
        $paymentMethod = PaymentMethod::factory()->create();

        Livewire::test(ViewPayment::class, ['paymentMethod' => $paymentMethod])
            ->set('confirmItemDelete', true) // <-- important!
            ->call('deletePaymentMethod', $paymentMethod)
            ->assertHasNoErrors();

        $this->assertSoftDeleted('pm_payment_methods', ['id' => $paymentMethod->id]);
    }

    //Soft Delete In List
    public function test_payment_method_can_be_soft_deleted_in_list()
    {
        $paymentMethod = PaymentMethod::factory()->create();

        $user = User::factory()->create();
        $user->givePermissionTo('payment-method-soft-delete');
    
        Livewire::actingAs($user)
            ->test(ViewPayments::class) 
            ->set('confirmItemDelete', $paymentMethod->id)
            ->call('deletePaymentMethod', $paymentMethod->id) 
            ->assertHasNoErrors();

        $this->assertSoftDeleted('pm_payment_methods', ['id' => $paymentMethod->id]);
    }

    //Permanently Deleted
    public function test_payment_method_can_be_permanently_deleted()
    {
         //creating a record then soft deleting
         $paymentMethod = PaymentMethod::factory()->create();
         $paymentMethod->delete();
 
     //set id
     Livewire::test(DeletedPayments::class)
         ->set('confirmItemDelete', $paymentMethod->id) //make modal true
         ->call('deletePaymentForever', $paymentMethod->id) //passes the id
         ->assertHasNoErrors();
 
     // Assert the payment record was permanently deleted
        $this->assertDatabaseMissing('pm_payment_methods', [
            'id' => $paymentMethod->id,
     ]);
    }

    public function test_payment_method_cannot_be_deleted_if_used_in_transactions()
    {
        // Create a user for the transaction
        $user = TransactionUser::factory()->create();

        // Create the transaction
        $transaction = Transaction::factory()->create([
            'created_by' => $user->id,
        ]);

        // Create a payment method
        $paymentMethod = PaymentMethod::factory()->create();

        // Create an invoice associated with the transaction
        $invoice = Invoice::factory()->create([
            'transaction_id' => $transaction->id,
        ]);

        // Create a payment that uses the created invoice and payment method
        $payment = Payment::factory()->create([
            'invoice_id' => $invoice->id,
            'payment_method_id' => $paymentMethod->id,
        ]);

    // Test single delete view component
    Livewire::test(ViewPayment::class, ['paymentMethod' => $paymentMethod])
        ->set('confirmItemDelete')
        ->call('deletePaymentMethod', $paymentMethod->id)
        ->assertSet('cannotDeleteItem', true)
        ->assertHasNoErrors();

    // Test list delete view component
    Livewire::test(ViewPayments::class, ['paymentMethod' => $paymentMethod])
        ->set('confirmItemDelete', $paymentMethod->id)
        ->call('deletePaymentMethod')
        ->assertSet('cannotDeleteItem', true)
        ->assertHasNoErrors();

    // Confirm the payment method was not soft deleted
    $this->assertDatabaseHas('pm_payment_methods', [
        'id' => $paymentMethod->id,
        'deleted_at' => null,
    ]);
    }
    }
