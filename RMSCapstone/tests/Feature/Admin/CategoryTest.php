<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\EventCategories\CreateEventCategory;
use App\Livewire\Admin\EventCategories\DeletedEventCategories;
use App\Livewire\Admin\EventCategories\EditEventCategory;
use App\Livewire\Admin\EventCategories\ViewEventCategories;
use App\Livewire\Admin\EventCategories\ViewEventCategory;
use App\Livewire\Admin\RoomCategories\CreateRoomCategory;
use App\Livewire\Admin\RoomCategories\DeletedRoomCategories;
use App\Livewire\Admin\RoomCategories\EditRoomCategory;
use App\Livewire\Admin\RoomCategories\ViewRoomCategories;
use App\Livewire\Admin\RoomCategories\ViewRoomCategory;
use App\Models\EventType;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\Transaction;
use App\Models\TransactionUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class CategoryTest extends TestCase
{
   //-------------------------- Test for Room Category and Event Types -------------------------//

   //-------------------------- ROOM CATEGORY -------------------------------------------------//
   
    //------------------------ TESTS TO SEE IF ROUTES ARE VISIBLE WITH PERMISSION -----------------//
    //List View
    public function test_category_view_component_is_visible()
    {
        Permission::findOrCreate('room-category-list');

        $user = User::factory()->create();
        $user->givePermissionTo('room-category-list');

        $this->actingAs($user)
            ->get(route('admin.room-categories'))
            ->assertSeeLivewire(ViewRoomCategories::class);
    }

    //Individual View
    public function test_category_single_view_component_is_visible()
    {
        Permission::findOrCreate('room-category-view');

        $user = User::factory()->create();
        $user->givePermissionTo('room-category-view');

        $roomCategory = PropertyCategory::factory()->create(); 

        $this->actingAs($user)
           ->get(route('admin.view-room-category', ['roomCategory' => $roomCategory->id]))
            ->assertSeeLivewire(ViewRoomCategory::class);
    }

    //Create View
    public function test_category_create_component_is_visible()
    {
        Permission::findOrCreate('room-category-create');

        $user = User::factory()->create();
        $user->givePermissionTo('room-category-create');

        $this->actingAs($user)
            ->get(route('admin.create-room-category'))
            ->assertSeeLivewire(CreateRoomCategory::class);
    }

    //Edit View
    public function test_category_edit_component_is_visible()
    {
       Permission::findOrCreate('room-category-edit');

       $user = User::factory()->create();
       $user->givePermissionTo('room-category-edit');

        $roomCategory = PropertyCategory::factory()->create();

        // Act as the user and visit the edit route 
        $this->actingAs($user)
        ->get(route('admin.edit-room-category', ['roomCategory' => $roomCategory->id]))
        ->assertSeeLivewire(EditRoomCategory::class);
    }

    //Soft Delete View
    public function test_category_soft_delete_component_is_visible()
    {
        Permission::findOrCreate('room-category-soft-delete');

        $user = User::factory()->create();
        $user->givePermissionTo('room-category-soft-delete');

        $this->actingAs($user)
            ->get(route('admin.deleted-room-categories'))
            ->assertSeeLivewire(DeletedRoomCategories::class);
    }


    //------------------- TESTS TO SEE IF ROUTES ARE VISIBLE WITHOUT PERMISSION ------------------//
    //List View
    public function test_category_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('room-category-list');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.room-categories'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ViewRoomCategories::class);
    }

    //Single View
    public function test_category_single_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('room-category-view');

        //user without permission
        $user = User::factory()->create();

        $roomCategory = PropertyCategory::factory()->create();

        $response = $this->actingAs($user)
        ->get(route('admin.view-room-category', $roomCategory->id));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ViewRoomCategory::class);
    }

    //Create View
    public function test_category_create_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('room-category-create');

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.create-room-category'));

        $response->assertForbidden(); 
        $response->assertDontSeeLivewire(CreateRoomCategory::class);
    }

    //Edit View
    public function test_category_edit_component_is_not_visible_without_permission()
    {
        
        Permission::findOrCreate('room-category-edit');

        $roomCategory = PropertyCategory::factory()->create();

        $user = User::factory()->create();

         $response = $this->actingAs($user)
        ->get(route('admin.edit-room-category', $roomCategory->id));

        $response->assertForbidden();
    }

    //Soft Delete View
    public function test_category_soft_delete_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('room-category-soft-delete');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.deleted-room-categories'));

        // Assert forbidden or redirect
        $response->assertForbidden(); 

        //Ensure the Livewire component is NOT rendered
        $response->assertDontSeeLivewire(DeletedRoomCategories::class);
    }


    //--------------------------------- CRUD TESTING ---------------------------------------//
    //Create - with no image upload
    public function test_category_can_be_created()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('room-category-create'); 

        $categoryData = PropertyCategory::factory()->make()->toArray(); 

        $livewire = Livewire::actingAs($user)
            ->test(CreateRoomCategory::class);

        foreach ($categoryData as $key => $value) {
            $livewire->set($key, $value);
        }

        $livewire
            ->call('saveCategory')
            ->assertSessionHas('message', 'Room Category successfully created!')
            ->assertRedirect(route('admin.room-categories'));

        $this->assertDatabaseHas('property_categories', [
            'name' => $categoryData['name'],
        ]);
    }

    //Edit - with no imageupload
    public function test_category_can_be_updated()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('room-category-edit');
    
        $roomCategory = PropertyCategory::factory()->create();
    
        // Create a set of new (fake) updated data
        $updatedData = PropertyCategory::factory()->make()->toArray();
    
        $livewire = Livewire::actingAs($user)
            ->test(EditRoomCategory::class, ['roomCategory' => $roomCategory]);
    
        foreach ($updatedData as $key => $value) {
            $livewire->set($key, $value);
        }
    
        $livewire
            ->call('updateCategory') 
            ->assertSessionHas('message', 'Room Category successfully updated!')
            ->assertRedirect(route('admin.room-categories'));
    
        $this->assertDatabaseHas('property_categories', [
            'id' => $roomCategory->id,
            'name' => $updatedData['name'],
        ]);
    }

    //Soft Deletes
    public function test_category_can_be_soft_deleted()
    {
        $roomCategory = PropertyCategory::factory()->create();

        Livewire::test(ViewRoomCategory::class, ['roomCategory' => $roomCategory])
            ->set('confirmItemDelete', $roomCategory->id)
            ->call('deleteRoomCategory')
            ->assertHasNoErrors();

        $this->assertSoftDeleted('property_categories', ['id' => $roomCategory->id]);
    }

    public function test_category_can_be_soft_deleted_in_list()
    {
        $roomCategory = PropertyCategory::factory()->create();

        $user = User::factory()->create();
        $user->givePermissionTo('room-category-soft-delete');
    
        Livewire::actingAs($user)
            ->test(ViewRoomCategories::class) 
            ->set('confirmItemDelete', $roomCategory->id)
            ->call('deleteRoomCategory', $roomCategory->id) 
            ->assertHasNoErrors();
    
        // Check if the category was soft-deleted in db
        $this->assertSoftDeleted('property_categories', ['id' => $roomCategory->id]);
    }

    public function test_category_can_be_permanently_deleted(){
         //creating a record then soft deleting
         $roomCategory = PropertyCategory::factory()->create();
         $roomCategory->delete();
 
     //set id
     Livewire::test(DeletedRoomCategories::class)
         ->set('confirmItemDelete', $roomCategory->id) //make modal true
         ->call('deleteRoomCategoryForever', $roomCategory->id) //passes the id
         ->assertHasNoErrors();
 
     // Assert the maintenance record was permanently deleted
         $this->assertDatabaseMissing('property_categories', [
            'id' => $roomCategory->id,
     ]);
    }

    public function test_category_cannot_be_deleted_if_used_in_transactions()
    {
        // Create a category
        $roomCategory = PropertyCategory::factory()->create();

        $room = Property::factory()->create([
            'property_category_id' => $roomCategory->id, 
        ]);

        // Mock the Livewire component and set confirmItemDelete to the category id
        //in ViewRoomCategory Delete
        Livewire::test(ViewRoomCategory::class, ['roomCategory' => $roomCategory])
            ->set('confirmItemDelete', $roomCategory->id)
            ->call('deleteRoomCategory')
            ->assertSet('cannotDeleteItem', true)
            ->assertHasNoErrors();

        //in list delete
        Livewire::test(ViewRoomCategories::class, ['roomCategory' => $roomCategory])
            ->set('confirmItemDelete', $roomCategory->id)
            ->call('deleteRoomCategory')
            ->assertSet('cannotDeleteItem', true)
            ->assertHasNoErrors();

            // Simulate bulk delete attempt via list component
        Livewire::test(ViewRoomCategories::class)
            ->set('selectedRows', [$roomCategory->id])
            ->set('confirmBulkDelete', true)
            ->call('deleteSelectedRows')
            ->assertSet('cannotDeleteItem', true)
            ->assertSet('confirmBulkDelete', false)
            ->assertHasNoErrors();

        // Assert category still exists in database (not soft deleted)
        $this->assertDatabaseHas('property_categories', [
            'id' => $roomCategory->id,
            'deleted_at' => null,
        ]);
    }

    public function test_bulk_delete_categories_successfully()
    {
    // Create activities (not attached to any transaction)
    $roomCategories = PropertyCategory::factory()->count(3)->create();

    $categoryIds = $roomCategories->pluck('id')->toArray();

    // Confirm all exist before deletion
    foreach ($categoryIds as $id) {
        $this->assertDatabaseHas('property_categories', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }

    // Attempt bulk delete using list component
    Livewire::test(ViewRoomCategories::class)
        ->set('selectedRows', $categoryIds)
        ->set('confirmBulkDelete', true)
        ->call('deleteSelectedRows')
        ->assertSet('confirmBulkDelete', false)
        ->assertHasNoErrors();

    // Confirm all are soft-deleted
    foreach ($categoryIds as $id) {
        $this->assertSoftDeleted('property_categories', [
            'id' => $id,
        ]);
    }
    }



    //-------------------------- EVENT CATEGORY ---------------------------------------------- //

    //------------------------ TESTS TO SEE IF ROUTES ARE VISIBLE WITH PERMISSION -----------------//
    //List View
    public function test_type_view_component_is_visible()
    {
        Permission::findOrCreate('event-category-list');

        $user = User::factory()->create();
        $user->givePermissionTo('event-category-list');

        $this->actingAs($user)
            ->get(route('admin.event-categories'))
            ->assertSeeLivewire(ViewEventCategories::class);
    }

    //Individual View
    public function test_type_single_view_component_is_visible()
    {
        Permission::findOrCreate('event-category-view');

        $user = User::factory()->create();
        $user->givePermissionTo('event-category-view');

        $eventCategory = EventType::factory()->create(); 

        $this->actingAs($user)
           ->get(route('admin.view-event-category', ['eventCategory' => $eventCategory->id]))
            ->assertSeeLivewire(ViewEventCategory::class);
    }

    //Create View
    public function test_type_create_component_is_visible()
    {
        Permission::findOrCreate('event-category-create');

        $user = User::factory()->create();
        $user->givePermissionTo('event-category-create');

        $this->actingAs($user)
            ->get(route('admin.create-event-category'))
            ->assertSeeLivewire(CreateEventCategory::class);
    }

    //Edit View
    public function test_type_edit_component_is_visible()
    {
       Permission::findOrCreate('event-category-edit');

       $user = User::factory()->create();
       $user->givePermissionTo('event-category-edit');

        $eventCategory = EventType::factory()->create();

        // Act as the user and visit the edit route 
        $this->actingAs($user)
        ->get(route('admin.edit-event-category', ['eventCategory' => $eventCategory->id]))
        ->assertSeeLivewire(EditEventCategory::class);
    }

    //Soft Delete View
    public function test_type_soft_delete_component_is_visible()
    {
        Permission::findOrCreate('event-category-soft-delete');

        $user = User::factory()->create();
        $user->givePermissionTo('event-category-soft-delete');

        $this->actingAs($user)
            ->get(route('admin.deleted-event-categories'))
            ->assertSeeLivewire(DeletedEventCategories::class);
    }

     //------------------- TESTS TO SEE IF ROUTES ARE VISIBLE WITHOUT PERMISSION ------------------//
    //List View
    public function test_type_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('event-category-list');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.event-categories'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ViewEventCategories::class);
    }

    //Single View
    public function test_type_single_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('event-category-view');

        //user without permission
        $user = User::factory()->create();

        $eventCategory = EventType::factory()->create();

        $response = $this->actingAs($user)
        ->get(route('admin.view-event-category', $eventCategory->id));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ViewEventCategory::class);
    }

    //Create View
    public function test_type_create_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('event-category-create');

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.create-event-category'));

        $response->assertForbidden(); 
        $response->assertDontSeeLivewire(CreateEventCategory::class);
    }

    //Edit View
    public function test_type_edit_component_is_not_visible_without_permission()
    {
        
        Permission::findOrCreate('event-category-edit');

        $eventCategory = EventType::factory()->create();

        $user = User::factory()->create();

         $response = $this->actingAs($user)
        ->get(route('admin.edit-event-category', $eventCategory->id));

        $response->assertForbidden();
    }

    //Soft Delete View
    public function test_type_soft_delete_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('event-category-soft-delete');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.deleted-event-categories'));

        // Assert forbidden or redirect
        $response->assertForbidden(); 

        //Ensure the Livewire component is NOT rendered
        $response->assertDontSeeLivewire(DeletedEventCategories::class);
    }



    //--------------------------------- CRUD TESTING ---------------------------------------//
    //Create - with no image upload
    public function test_type_can_be_created()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('event-category-create'); 

        $categoryData = EventType::factory()->make()->toArray(); 

        $livewire = Livewire::actingAs($user)
            ->test(CreateEventCategory::class);

        foreach ($categoryData as $key => $value) {
            $livewire->set($key, $value);
        }

        $livewire
            ->call('saveEventCategory')
            ->assertSessionHas('message', 'Event Category successfully created!')
            ->assertRedirect(route('admin.event-categories'));

        $this->assertDatabaseHas('event_types', [
            'name' => $categoryData['name'],
        ]);
    }

    //Edit - with no imageupload
    public function test_type_can_be_updated()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('event-category-edit');
    
        $eventCategory = EventType::factory()->create();
    
        // Create a set of new (fake) updated data
        $updatedData = EventType::factory()->make()->toArray();
    
        $livewire = Livewire::actingAs($user)
            ->test(EditEventCategory::class, ['eventCategory' => $eventCategory]);
    
        foreach ($updatedData as $key => $value) {
            $livewire->set($key, $value);
        }
    
        $livewire
            ->call('updateEventCategory') 
            ->assertSessionHas('message', 'Event Category successfully updated!')
            ->assertRedirect(route('admin.event-categories'));
    
        $this->assertDatabaseHas('event_types', [
            'id' => $eventCategory->id,
            'name' => $updatedData['name'],
        ]);
    }

    //Soft Deletes
    public function test_type_can_be_soft_deleted()
    {
        $eventCategory = EventType::factory()->create();

        Livewire::test(ViewEventCategory::class, ['eventCategory' => $eventCategory])
            ->set('confirmItemDelete', $eventCategory->id)
            ->call('deleteEventCategory')
            ->assertHasNoErrors();

        $this->assertSoftDeleted('property_categories', ['id' => $eventCategory->id]);
    }

    public function test_type_can_be_soft_deleted_in_list()
    {
        $eventCategory = EventType::factory()->create();

        $user = User::factory()->create();
        $user->givePermissionTo('event-category-soft-delete');
    
        Livewire::actingAs($user)
            ->test(ViewEventCategories::class) 
            ->set('confirmItemDelete', $eventCategory->id)
            ->call('deleteEventCategory', $eventCategory->id) 
            ->assertHasNoErrors();
    
        // Check if the category was soft-deleted in db
        $this->assertSoftDeleted('event_types', ['id' => $eventCategory->id]);
    }

    public function test_type_can_be_permanently_deleted(){
         //creating a record then soft deleting
         $eventCategory = EventType::factory()->create();
         $eventCategory->delete();
 
     //set id
     Livewire::test(DeletedEventCategories::class)
         ->set('confirmItemDelete', $eventCategory->id) //make modal true
         ->call('deleteEventCategoryForever', $eventCategory->id) //passes the id
         ->assertHasNoErrors();
 
     // Assert the maintenance record was permanently deleted
         $this->assertDatabaseMissing('event_types', [
            'id' => $eventCategory->id,
     ]);
    }

    public function test_type_cannot_be_deleted_if_used_in_transactions()
    {
        // Create a category
        $eventCategory = EventType::factory()->create();

        // Create a transaction user (creator)
        $user = TransactionUser::factory()->create();

        // Create a transaction and attach the activity to it
        $transaction = Transaction::factory()->create([
            'event_type_id' => $eventCategory->id,
        ]);

        // Mock the Livewire component and set confirmItemDelete to the category id
        //in ViewEventCategory Delete
        Livewire::test(ViewEventCategory::class, ['eventCategory' => $eventCategory])
            ->set('confirmItemDelete', $eventCategory->id)
            ->call('deleteEventCategory')
            ->assertSet('cannotDeleteItem', true)
            ->assertHasNoErrors();

        //in list delete
        Livewire::test(ViewEventCategories::class, ['eventCategory' => $eventCategory])
            ->set('confirmItemDelete', $eventCategory->id)
            ->call('deleteEventCategory')
            ->assertSet('cannotDeleteItem', true)
            ->assertHasNoErrors();

        // Simulate bulk delete attempt via list component
        Livewire::test(ViewEventCategories::class)
            ->set('selectedRows', [$eventCategory->id])
            ->set('confirmBulkDelete', true)
            ->call('deleteSelectedRows')
            ->assertSet('cannotDeleteItem', true)
            ->assertSet('confirmBulkDelete', false)
            ->assertHasNoErrors();

        // Assert category still exists in database (not soft deleted)
        $this->assertDatabaseHas('event_types', [
            'id' => $eventCategory->id,
            'deleted_at' => null,
        ]);
    }

    public function test_bulk_delete_types_successfully()
    {
        $eventCategories = EventType::factory()->count(3)->create();

        $categoryIds = $eventCategories->pluck('id')->toArray();

        // Confirm all exist before deletion
        foreach ($categoryIds as $id) {
            $this->assertDatabaseHas('event_types', [
                'id' => $id,
                'deleted_at' => null,
            ]);
        }

        // Attempt bulk delete using list component
        Livewire::test(ViewEventCategories::class)
            ->set('selectedRows', $categoryIds)
            ->set('confirmBulkDelete', true)
            ->call('deleteSelectedRows')
            ->assertSet('confirmBulkDelete', false)
            ->assertHasNoErrors();

        // Confirm all are soft-deleted
        foreach ($categoryIds as $id) {
            $this->assertSoftDeleted('event_types', [
                'id' => $id,
            ]);
        }
    }
}
