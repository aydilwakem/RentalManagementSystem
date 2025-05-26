<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Activities\CreateActivity;
use App\Livewire\Admin\Activities\DeletedActivities;
use App\Livewire\Admin\Activities\EditActivity;
use App\Livewire\Admin\Activities\ViewActivities;
use App\Livewire\Admin\Activities\ViewActivity;
use App\Models\Activity;
use App\Models\Transaction;
use App\Models\TransactionUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ActivityTest extends TestCase
{
   //TESTS TO SEE IF ROUTES ARE VISIBLE WITH PERMISSION

    //List View
    public function test_activity_view_component_is_visible()
    {
        Permission::findOrCreate('activity-list');

        $user = User::factory()->create();
        $user->givePermissionTo('activity-list');

        $this->actingAs($user)
            ->get(route('admin.activities'))
            ->assertSeeLivewire(ViewActivities::class);
    }

    //Individual View
    public function test_activity_single_view_component_is_visible()
    {
        Permission::findOrCreate('activity-view');

        $user = User::factory()->create();
        $user->givePermissionTo('activity-view');

        $activity = Activity::factory()->create(); 

        $this->actingAs($user)
           ->get(route('admin.view-activity', ['activity' => $activity->id]))
            ->assertSeeLivewire(ViewActivity::class);
    }

    //Create View
    public function test_activity_create_component_is_visible()
    {
        Permission::findOrCreate('activity-create');

        $user = User::factory()->create();
        $user->givePermissionTo('activity-create');

        $this->actingAs($user)
            ->get(route('admin.create-activity'))
            ->assertSeeLivewire(CreateActivity::class);
    }

    //Edit View
    public function test_activity_edit_component_is_visible()
    {
      
       Permission::findOrCreate('activity-edit');

       $user = User::factory()->create();
       $user->givePermissionTo('activity-edit');

        $activity = Activity::factory()->create();

        // Act as the user and visit the edit route 
        $this->actingAs($user)
        ->get(route('admin.edit-activity', ['activity' => $activity->id]))
        ->assertSeeLivewire(EditActivity::class);
    }

    //Soft Delete View
    public function test_activity_soft_delete_component_is_visible()
    {
        Permission::findOrCreate('activity-soft-delete');

        $user = User::factory()->create();
        $user->givePermissionTo('activity-soft-delete');

        $this->actingAs($user)
            ->get(route('admin.deleted-activities'))
            ->assertSeeLivewire(DeletedActivities::class);
    }


    //TESTS TO SEE IF ROUTES ARE VISIBLE WITHOUT PERMISSION

    //List View
    public function test_activity_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('activity-list');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.activities'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ViewActivities::class);
    }

    //Single View
    public function test_activity_single_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('activity-view');

        //user without permission
        $user = User::factory()->create();

        $activity = Activity::factory()->create();

        $response = $this->actingAs($user)
        ->get(route('admin.view-activity', $activity->id));

        $response->assertForbidden(); 


        $response->assertDontSeeLivewire(ViewActivity::class);
    }

    //Create View
    public function test_activity_create_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('activity-create');


        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.create-activity'));

        $response->assertForbidden(); 


        $response->assertDontSeeLivewire(CreateActivity::class);
    }

    //Edit View
    public function test_activity_edit_component_is_not_visible_without_permission()
    {
        
        Permission::findOrCreate('activity-edit');

        $activity = Activity::factory()->create();


        $user = User::factory()->create();

         $response = $this->actingAs($user)
        ->get(route('admin.edit-activity', $activity->id));

        $response->assertForbidden();
    }

    //Soft Delete View
    public function test_activity_soft_delete_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('activity-soft-delete');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.deleted-activities'));

        // Assert forbidden or redirect
        $response->assertForbidden(); 

        //Ensure the Livewire component is NOT rendered
        $response->assertDontSeeLivewire(DeletedActivities::class);
    }

    //CRUD TESTING
    //Create - with no image upload
    public function test_activity_can_be_created()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('activity-create'); 

        $activityData = Activity::factory()->make()->toArray(); 

        $livewire = Livewire::actingAs($user)
            ->test(CreateActivity::class);

        foreach ($activityData as $key => $value) {
            $livewire->set($key, $value);
        }

        $livewire
            ->call('saveActivity')
            ->assertSessionHas('message', 'Activity successfully created!')
            ->assertRedirect(route('admin.activities'));

        $this->assertDatabaseHas('prd_activities', [
            'name' => $activityData['name'],
        ]);
    }

    //Edit - with no imageupload
    public function test_activity_can_be_updated()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('activity-edit');
    
        $activity = Activity::factory()->create();
    
        // Create a set of new (fake) updated data
        $updatedData = Activity::factory()->make()->toArray();
    
        $livewire = Livewire::actingAs($user)
            ->test(EditActivity::class, ['activity' => $activity]);
    
        foreach ($updatedData as $key => $value) {
            $livewire->set($key, $value);
        }
    
        $livewire
            ->call('updateActivity') 
            ->assertSessionHas('message', 'Activity successfully updated!')
            ->assertRedirect(route('admin.activities'));
    
        $this->assertDatabaseHas('prd_activities', [
            'id' => $activity->id,
            'name' => $updatedData['name'],
        ]);
    }

    //Soft Deletes
    public function test_activity_can_be_soft_deleted()
    {
        $activity = Activity::factory()->create();

        Livewire::test(ViewActivity::class, ['activity' => $activity])
            ->set('confirmItemDelete', $activity->id)
            ->call('deleteActivity')
            ->assertHasNoErrors();

        $this->assertSoftDeleted('prd_activities', ['id' => $activity->id]);
    }

    public function test_activity_can_be_soft_deleted_in_list()
    {
        $activity = Activity::factory()->create();

        $user = User::factory()->create();
        $user->givePermissionTo('activity-soft-delete');
    
        Livewire::actingAs($user)
            ->test(ViewActivities::class) 
            ->set('confirmItemDelete', $activity->id)
            ->call('deleteActivity', $activity->id) 
            ->assertHasNoErrors();
    
        // Check if the activity was soft-deleted in db
        $this->assertSoftDeleted('prd_activities', ['id' => $activity->id]);
    }

    public function test_activity_can_be_permanently_deleted(){
         //creating a record then soft deleting
         $activity = Activity::factory()->create();
         $activity->delete();
 
     //set id
     Livewire::test(DeletedActivities::class)
         ->set('confirmItemDelete', $activity->id) //make modal true
         ->call('deleteActivityForever', $activity->id) //passes the id
         ->assertHasNoErrors();
 
     // Assert the maintenance record was permanently deleted
         $this->assertDatabaseMissing('prd_activities', [
             'id' => $activity->id,
     ]);
 
    }

    public function test_activity_cannot_be_deleted_if_used_in_transactions()
    {
    // Create an activity
    $activity = Activity::factory()->create();

    // Create a transaction user (creator)
    $user = TransactionUser::factory()->create();

    // Create a transaction and attach the activity to it
    $transaction = Transaction::factory()->create([
        'created_by' => $user->id,
    ]);

    // Attach activity to transaction (using pivot table 'transaction_activities')
    $transaction->activities()->attach($activity->id, [
        'quantity' => 1,
        'amount' => 100,
    ]);

    // Mock the Livewire component and set confirmItemDelete to the activity id
    //in ViewActivity Delete
    Livewire::test(ViewActivity::class, ['activity' => $activity])
        ->set('confirmItemDelete', $activity->id)
        ->call('deleteActivity')
        ->assertSet('cannotDeleteItem', true)
        ->assertHasNoErrors();

    //in ViewActivities Delete
    Livewire::test(ViewActivities::class, ['activity' => $activity])
        ->set('confirmItemDelete', $activity->id)
        ->call('deleteActivity')
        ->assertSet('cannotDeleteItem', true)
        ->assertHasNoErrors();

        // Simulate bulk delete attempt via ViewActivities component
    Livewire::test(ViewActivities::class)
        ->set('selectedRows', [$activity->id])
        ->set('confirmBulkDelete', true)
        ->call('deleteSelectedRows')
        ->assertSet('cannotDeleteItem', true)
        ->assertSet('confirmBulkDelete', false)
        ->assertHasNoErrors();

    // Assert activity still exists in database (not soft deleted)
    $this->assertDatabaseHas('prd_activities', [
        'id' => $activity->id,
        'deleted_at' => null,
    ]);
    }

public function test_bulk_delete_activities_successfully()
    {
    // Create activities (not attached to any transaction)
    $activities = Activity::factory()->count(3)->create();

    $activityIds = $activities->pluck('id')->toArray();

    // Confirm all exist before deletion
    foreach ($activityIds as $id) {
        $this->assertDatabaseHas('prd_activities', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }

    // Attempt bulk delete using ViewActivities component
    Livewire::test(ViewActivities::class)
        ->set('selectedRows', $activityIds)
        ->set('confirmBulkDelete', true)
        ->call('deleteSelectedRows')
        ->assertSet('confirmBulkDelete', false)
        ->assertHasNoErrors();

    // Confirm all are soft-deleted
    foreach ($activityIds as $id) {
        $this->assertSoftDeleted('prd_activities', [
            'id' => $id,
        ]);
    }
    }



}
