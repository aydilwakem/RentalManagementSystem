<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Maintenance\CreateMaintenance;
use App\Livewire\Admin\Maintenance\DeletedMaintenances;
use App\Livewire\Admin\Maintenance\EditMaintenance;
use App\Livewire\Admin\Maintenance\OldMaintenances;
use App\Livewire\Admin\Maintenance\ViewMaintenance;
use App\Livewire\Admin\Maintenance\ViewMaintenances;
use App\Models\Maintenance;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class MaintenanceTest extends TestCase
{
    //TESTS TO SEE IF ROUTES ARE VISIBLE WITH PERMISSION
    //List View
    public function test_maintenance_view_component_is_visible()
    {
        Permission::findOrCreate('maintenance-list');

        $user = User::factory()->create();
        $user->givePermissionTo('maintenance-list');

        $this->actingAs($user)
            ->get(route('admin.maintenances'))
            ->assertSeeLivewire(ViewMaintenances::class);
    }

     //Individual View
    public function test_maintenance_single_view_component_is_visible()
    {
        Permission::findOrCreate('maintenance-view');

        $user = User::factory()->create();
        $user->givePermissionTo('maintenance-view');

        $maintenance = Maintenance::factory()->create(); 

        $this->actingAs($user)
           ->get(route('admin.view-maintenance', ['maintenance' => $maintenance->id]))
            ->assertSeeLivewire(ViewMaintenance::class);
    }

    //Create View
    public function test_maintenance_create_component_is_visible()
    {
        Permission::findOrCreate('maintenance-create');

        $user = User::factory()->create();
        $user->givePermissionTo('maintenance-create');

        $this->actingAs($user)
            ->get(route('admin.create-maintenance'))
            ->assertSeeLivewire(CreateMaintenance::class);
    }

    //Edit View
    public function test_maintenance_edit_component_is_visible()
    {
       Permission::findOrCreate('maintenance-edit');

       $user = User::factory()->create();
       $user->givePermissionTo('maintenance-edit');

        $maintenance = Maintenance::factory()->create();

        // Act as the user and visit the edit route 
        $this->actingAs($user)
        ->get(route('admin.edit-maintenance', ['maintenance' => $maintenance->id]))
        ->assertSeeLivewire(EditMaintenance::class);
    }

    //Soft Delete View
    public function test_maintenance_soft_delete_component_is_visible()
    {
        Permission::findOrCreate('maintenance-soft-delete');

        $user = User::factory()->create();
        $user->givePermissionTo('maintenance-soft-delete');

        $this->actingAs($user)
            ->get(route('admin.deleted-maintenances'))
            ->assertSeeLivewire(DeletedMaintenances::class);
    }

    //TESTS TO SEE IF ROUTES ARE VISIBLE WITHOUT PERMISSION

     //List View
    public function test_maintenance_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('maintenance-list');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.maintenances'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ViewMaintenances::class);
    }

    //Single View
    public function test_maintenance_single_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('maintenance-view');

        //user without permission
        $user = User::factory()->create();

        $maintenance = Maintenance::factory()->create();

        $response = $this->actingAs($user)
        ->get(route('admin.view-maintenance', $maintenance->id));

        $response->assertForbidden(); 


        $response->assertDontSeeLivewire(ViewMaintenance::class);
    }

    //Create View
    public function test_maintenance_create_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('maintenance-create');

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.create-maintenance'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(CreateMaintenance::class);
    }

    //Edit View
    public function test_maintenance_edit_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('maintenance-edit');

        $maintenance = Maintenance::factory()->create();
        $user = User::factory()->create();

         $response = $this->actingAs($user)
        ->get(route('admin.edit-maintenance', $maintenance->id));

        $response->assertForbidden();
    }

    //Soft Delete View
    public function test_maintenance_soft_delete_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('maintenance-soft-delete');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.deleted-maintenances'));

        // Assert forbidden or redirect
        $response->assertForbidden(); 

        //Ensure the Livewire component is NOT rendered
        $response->assertDontSeeLivewire(DeletedMaintenances::class);
    }


    // -------------------------- CRUD TESTING
    //Create - with no image upload
    public function test_maintenance_can_be_created()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('maintenance-create'); 

        $maintenanceData = Maintenance::factory()->make()->toArray(); 

        $livewire = Livewire::actingAs($user)
            ->test(CreateMaintenance::class);

        foreach ($maintenanceData as $key => $value) {
            $livewire->set($key, $value);
        }

        $livewire
            ->call('saveMaintenance');

        $this->assertDatabaseHas('mnt_maintenance', [
            'name' => $maintenanceData['name'],
        ]);
    }

    //Edit - with no imageupload
    public function test_maintenance_can_be_updated()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('maintenance-edit');
    
        $maintenance = Maintenance::factory()->create();
    
        // Updated data with null resolved_at
        $updatedData = Maintenance::factory()->make([
            'resolved_at' => null,
        ])->toArray();
    
        $livewire = Livewire::actingAs($user)
            ->test(EditMaintenance::class, ['maintenance' => $maintenance]);
    
        foreach ($updatedData as $key => $value) {
            $livewire->set($key, $value);
        }
    
        $livewire
            ->call('updateMaintenance') 
            ->assertSessionHas('message', 'Maintenance item successfully updated!')
            ->assertRedirect(route('admin.maintenances'));
    
        $this->assertDatabaseHas('mnt_maintenance', [
            'id' => $maintenance->id,
            'name' => $updatedData['name'],
        ]);
    }

    public function test_maintenance_can_be_updated_with_resolved_at()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('maintenance-edit');

        $maintenance = Maintenance::factory()->create();

        $resolvedDate = now()->addDay()->toDateString();

        // Set resolved_at 
        $updatedData = Maintenance::factory()->make([
            'resolved_at' => now()->addDay()->format('Y-m-d'),
        ])->toArray();

        $livewire = Livewire::actingAs($user)
            ->test(EditMaintenance::class, ['maintenance' => $maintenance]);

        foreach ($updatedData as $key => $value) {
            $livewire->set($key, $value);
        }

        $livewire
            ->call('updateMaintenance')
            ->assertSessionHas('message', 'Maintenance successfully resolved! Moved to Old Maintenances')
            ->assertRedirect(route('admin.maintenances'));

        $this->assertDatabaseHas('mnt_maintenance', [
            'id' => $maintenance->id,
            'resolved_at' => $updatedData['resolved_at'],
        ]);
    }

    //Soft Delete
    public function test_maintenance_can_be_soft_deleted()
    {
         $maintenance = Maintenance::factory()->create();

         Livewire::test(ViewMaintenance::class, ['maintenance' => $maintenance])
            ->set('confirmItemDelete', true) // <-- important!
            ->call('deleteMaintenanceItem', $maintenance)
            ->assertHasNoErrors();

        $this->assertSoftDeleted('mnt_maintenance', ['id' => $maintenance->id]);
    }

    //Soft Delete In List
    public function test_maintenance_can_be_soft_deleted_in_list()
    {
        $maintenance = Maintenance::factory()->create();

        $user = User::factory()->create();
        $user->givePermissionTo('maintenance-soft-delete');
    
        Livewire::actingAs($user)
            ->test(ViewMaintenances::class) 
            ->set('confirmItemDelete', $maintenance->id)
            ->call('deleteMaintenances', $maintenance->id) 
            ->assertHasNoErrors();

        $this->assertSoftDeleted('mnt_maintenance', ['id' => $maintenance->id]);
    }

    public function test_maintenance_can_be_permanently_deleted(){
         //creating a record then soft deleting
         $maintenance = Maintenance::factory()->create();
         $maintenance->delete();
 
     //set id
     Livewire::test(DeletedMaintenances::class)
         ->set('confirmItemDelete', $maintenance->id) //make modal true
         ->call('deleteMaintenanceForever', $maintenance->id) //passes the id
         ->assertHasNoErrors();
 
     // Assert the maintenance record was permanently deleted
         $this->assertDatabaseMissing('mnt_maintenance', [
            'id' => $maintenance->id,
     ]);
    }

    //Bulk Deletes
    public function test_bulk_delete_maintenances_successfully()
    {
    // Create maintenances 
    $maintenances = Maintenance::factory()->count(3)->create();

    $maintenanceIds = $maintenances->pluck('id')->toArray();

    // Confirm all exist before deletion
    foreach ($maintenanceIds as $id) {
        $this->assertDatabaseHas('mnt_maintenance', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }

    // Attempt bulk delete using ViewMaintenances component
    Livewire::test(ViewMaintenances::class)
        ->set('selectedRows', $maintenanceIds)
        ->set('confirmBulkDelete', true)
        ->call('deleteSelectedRows')
        ->assertSet('confirmBulkDelete', false)
        ->assertHasNoErrors();

    //In OldMaintenances
    // Attempt bulk delete using OldMaintenances component
    Livewire::test(OldMaintenances::class)
        ->set('selectedRows', $maintenanceIds)
        ->set('confirmBulkDelete', true)
        ->call('deleteSelectedRows')
        ->assertSet('confirmBulkDelete', false)
        ->assertHasNoErrors();

    // Confirm all are soft-deleted
    foreach ($maintenanceIds as $id) {
        $this->assertSoftDeleted('mnt_maintenance', [
            'id' => $id,
        ]);
    }
    }
}
