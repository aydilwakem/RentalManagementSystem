<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Tenants\CreateTenant;
use App\Livewire\Admin\Tenants\DeletedTenants;
use App\Livewire\Admin\Tenants\EditTenant;
use App\Livewire\Admin\Tenants\ViewTenant;
use App\Livewire\Admin\Tenants\ViewTenants;
use App\Models\Transaction;
use App\Models\TransactionUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class TenantTest extends TestCase
{
    // -------------------------- TENANT TEST ------------------------------------------- //
    //------------------------ TESTS TO SEE IF ROUTES ARE VISIBLE WITH PERMISSION -----------------//
    //List View
    public function test_tenant_view_component_is_visible()
    {
        Permission::findOrCreate('tenant-list');

        $user = User::factory()->create();
        $user->givePermissionTo('tenant-list');

        $this->actingAs($user)
            ->get(route('admin.tenants'))
            ->assertSeeLivewire(ViewTenants::class);
    }

    //Individual View
    public function test_tenant_single_view_component_is_visible()
    {
        Permission::findOrCreate('tenant-view');

        $user = User::factory()->create();
        $user->givePermissionTo('tenant-view');

        $tenant = TransactionUser::factory()->create([
        'trn_user_type' => 'tenant',
        ]);

        $this->actingAs($user)
           ->get(route('admin.view-tenant', ['tenant' => $tenant->id]))
            ->assertSeeLivewire(ViewTenant::class);
    }

    //Create View
    public function test_tenant_create_component_is_visible()
    {
        Permission::findOrCreate('tenant-create');

        $user = User::factory()->create();
        $user->givePermissionTo('tenant-create');

        $this->actingAs($user)
            ->get(route('admin.create-tenant'))
            ->assertSeeLivewire(CreateTenant::class);
    }

    //Edit View
    public function test_tenant_edit_component_is_visible()
    {
       Permission::findOrCreate('tenant-edit');

       $user = User::factory()->create();
       $user->givePermissionTo('tenant-edit');

        $tenant = TransactionUser::factory()->create([
        'trn_user_type' => 'tenant',
        ]);

        // Act as the user and visit the edit route 
        $this->actingAs($user)
        ->get(route('admin.edit-tenant', ['tenant' => $tenant->id]))
        ->assertSeeLivewire(EditTenant::class);
    }

    //Soft Delete View
    public function test_tenant_soft_delete_component_is_visible()
    {
        Permission::findOrCreate('tenant-soft-delete');

        $user = User::factory()->create();
        $user->givePermissionTo('tenant-soft-delete');

        $this->actingAs($user)
            ->get(route('admin.deleted-tenants'))
            ->assertSeeLivewire(DeletedTenants::class);
    }

    //---------------------- TESTS TO SEE IF ROUTES ARE VISIBLE WITHOUT PERMISSION
    //List View
    public function test_tenants_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('tenant-list');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.tenants'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ViewTenants::class);
    }

    //Single View
    public function test_tenant_single_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('tenant-view');

        //user without permission
        $user = User::factory()->create();

        $tenant = TransactionUser::factory()->create([
        'trn_user_type' => 'tenant',
        ]);

        $response = $this->actingAs($user)
        ->get(route('admin.view-tenant', $tenant->id));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ViewTenant::class);
    }

    //Create View
    public function test_tenant_create_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('tenant-create');

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.create-tenant'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(CreateTenant::class);
    }

    //Edit View
    public function test_tenant_edit_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('tenant-edit');

        $tenant = TransactionUser::factory()->create([
            'trn_user_type' => 'tenant'
        ]);

        $user = User::factory()->create();

         $response = $this->actingAs($user)
        ->get(route('admin.edit-tenant', $tenant->id));

        $response->assertForbidden();
    }

    //Soft Delete View
    public function test_tenant_soft_delete_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('tenant-soft-delete');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.deleted-tenants'));

        // Assert forbidden or redirect
        $response->assertForbidden(); 

        //Ensure the Livewire component is NOT rendered
        $response->assertDontSeeLivewire(DeletedTenants::class);
    }

    // -------------------------------------- CRUD TESTING -----------------------------//
    //Create - with no image upload
    public function test_tenant_can_be_created()
    {
       $user = User::factory()->create();
    $user->givePermissionTo('tenant-create');

    // Use factory to make a fake tenant (not yet saved to DB)
    $tenant = TransactionUser::factory()->make([
        'trn_user_type' => 'tenant',
    ]);

    Livewire::actingAs($user)
        ->test(CreateTenant::class)
        ->set('trn_user_type', $tenant->trn_user_type)
        ->set('first_name', $tenant->first_name)
        ->set('middle_name', $tenant->middle_name)
        ->set('last_name', $tenant->last_name)
        ->set('suffix', $tenant->suffix)
        ->set('email', $tenant->email)
        ->set('company_name', $tenant->company_name)
        ->set('contact_number', $tenant->contact_number)
        ->set('city_municipality', $tenant->city_municipality)
        ->set('country', $tenant->country)
        ->call('saveTenant');

    $this->assertDatabaseHas('trn_users', [
        'first_name' => $tenant->first_name,
        'last_name' => $tenant->last_name,
        'email' => $tenant->email,
    ]);
    }

    //Edit 
    public function test_tenant_can_be_updated()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('tenant-edit');
    
        $tenant = TransactionUser::factory()->create([
        'trn_user_type' => 'tenant',
    ]);
    
        // Create a set of new (fake) updated data
        $updatedData = TransactionUser::factory()->make()->toArray();
    
        $livewire = Livewire::actingAs($user)
            ->test(EditTenant::class, ['tenant' => $tenant]);
    
        foreach ($updatedData as $key => $value) {
            $livewire->set($key, $value);
        }
    
        $livewire
            ->call('updateTenant') 
            ->assertSessionHas('message', 'Tenant successfully updated!')
            ->assertRedirect(route('admin.tenants'));
    
        $this->assertDatabaseHas('trn_users', [
            'id' => $tenant->id,
            'first_name' => $updatedData['first_name'],
        ]);
    }

    //Soft Delete
    public function test_tenant_can_be_soft_deleted()
    {
        $tenant = TransactionUser::factory()->create([
            'trn_user_type' => 'tenant', 
        ]);

        Livewire::test(ViewTenant::class, ['tenant' => $tenant])
            ->set('confirmItemDelete',  $tenant->id) 
            ->call('deleteTenant')
            ->assertHasNoErrors();

        $this->assertSoftDeleted('trn_users', ['id' => $tenant->id]);
    }

    //Soft Delete In List
    public function test_tenants_can_be_soft_deleted_in_list()
    {
        $tenant = TransactionUser::factory()->create([
            'trn_user_type' => 'tenant', 
        ]);

        $user = User::factory()->create();
        $user->givePermissionTo('tenant-soft-delete');
    
        Livewire::actingAs($user)
            ->test(ViewTenants::class) 
            ->set('confirmItemDelete', $tenant->id)
            ->call('deleteTenant', $tenant->id) 
            ->assertHasNoErrors();

        $this->assertSoftDeleted('trn_users', ['id' => $tenant->id]);
    }

    //Permanently Deleted
    public function test_tenant_can_be_permanently_deleted()
    {
        //creating a record then soft deleting
        $tenant = TransactionUser::factory()->create([
            'trn_user_type' => 'tenant', 
        ]);
        $tenant->delete();
 
     //set id
     Livewire::test(DeletedTenants::class)
         ->set('confirmItemDelete', $tenant->id) //make modal true
         ->call('deleteTenantForever', $tenant->id) //passes the id
         ->assertHasNoErrors();
 
     // Assert the payment record was permanently deleted
        $this->assertDatabaseMissing('trn_users', [
            'id' => $tenant->id,
     ]);
    }

    //Bulk Deletes
    public function test_bulk_delete_tenants_successfully()
    {
        // Create 3 tenants using factory (with required fields populated)
    $tenants = TransactionUser::factory()
        ->count(3)
        ->create([
            'trn_user_type' => 'tenant',
        ]);

    $tenantIds = $tenants->pluck('id')->toArray();

    // Ensure all were created
    foreach ($tenants as $tenant) {
        $this->assertDatabaseHas('trn_users', [
            'id' => $tenant->id,
            'first_name' => $tenant->first_name,
            'last_name' => $tenant->last_name,
            'deleted_at' => null,
        ]);
    }

    // Run Livewire component to delete selected tenants
    Livewire::test(ViewTenants::class)
        ->set('selectedRows', $tenantIds)
        ->set('confirmBulkDelete', true)
        ->call('deleteSelectedRows')
        ->assertSet('confirmBulkDelete', false)
        ->assertHasNoErrors();

    // Ensure all are soft-deleted
    foreach ($tenantIds as $id) {
        $this->assertSoftDeleted('trn_users', [
            'id' => $id,
        ]);
    }
    }

    public function test_tenant_cannot_be_deleted_if_used_in_transactions()
    {
        // Create a category
        $tenant = TransactionUser::factory()->create([
            'trn_user_type' => 'tenant'
        ]);

        // Create a transaction user (creator)
        $user = TransactionUser::factory()->create();

        // Create a transaction and attach the user to it
        $transaction = Transaction::factory()->create([
            'reservation_type_id' => 1, 
            'created_by' => $tenant->id,
        ]);

        // Mock the Livewire component and set confirmItemDelete to the category id
        //in single Delete
        Livewire::test(ViewTenant::class, ['tenant' => $tenant])
            ->set('confirmItemDelete', $tenant->id)
            ->call('deleteTenant')
            ->assertSet('cannotDeleteItem', true)
            ->assertHasNoErrors();

        //in list delete
        Livewire::test(ViewTenants::class)
        ->set('confirmItemDelete', $tenant->id) // for single delete from list
        ->call('deleteTenant', $tenant->id)
        ->assertSet('cannotDeleteItem', true);

        // Simulate bulk delete attempt via list component
        Livewire::test(ViewTenants::class)
            ->set('selectedRows', [$tenant->id])
            ->set('confirmBulkDelete', true)
            ->call('deleteSelectedRows')
            ->assertSet('cannotDeleteItem', true)
            ->assertSet('confirmBulkDelete', false)
            ->assertHasNoErrors();

        // Assert category still exists in database (not soft deleted)
        $this->assertDatabaseHas('trn_users', [
            'id' => $tenant->id,
            'deleted_at' => null,
        ]);
    }
}
