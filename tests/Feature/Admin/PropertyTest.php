<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\EventHalls\CreateEventHall;
use App\Livewire\Admin\EventHalls\DeletedEventHalls;
use App\Livewire\Admin\EventHalls\EditEventHall;
use App\Livewire\Admin\EventHalls\ViewEventHall;
use App\Livewire\Admin\EventHalls\ViewEventHalls;
use App\Livewire\Admin\Properties\CreateProperty;
use App\Livewire\Admin\Properties\DeletedProperties;
use App\Livewire\Admin\Properties\EditProperty;
use App\Livewire\Admin\Properties\ViewProperties;
use App\Livewire\Admin\Properties\ViewProperty;
use App\Livewire\Admin\Rooms\CreateRoom;
use App\Livewire\Admin\Rooms\DeletedRooms;
use App\Livewire\Admin\Rooms\EditRoom;
use App\Livewire\Admin\Rooms\ViewRoom;
use App\Livewire\Admin\Rooms\ViewRooms;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\TransactionUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PropertyTest extends TestCase
{
    //-------------------TESTS TO SEE IF ROUTES ARE VISIBLE WITH PERMISSION //
   //List View - House
   public function test_house_view_component_is_visible()
   {
       Permission::findOrCreate('house-list');

       $user = User::factory()->create();
       $user->givePermissionTo('house-list');

       $this->actingAs($user)
           ->get(route('admin.properties'))
           ->assertSeeLivewire(ViewProperties::class);
   }

   //List View - Room
   public function test_room_view_component_is_visible()
   {
       Permission::findOrCreate('room-list');

       $user = User::factory()->create();
       $user->givePermissionTo('room-list');

       $this->actingAs($user)
           ->get(route('admin.rooms'))
           ->assertSeeLivewire(ViewRooms::class);
   }

   //List View - Hall
   public function test_hall_view_component_is_visible()
   {
       Permission::findOrCreate('event-hall-list');

       $user = User::factory()->create();
       $user->givePermissionTo('event-hall-list');

       $this->actingAs($user)
           ->get(route('admin.event-halls'))
           ->assertSeeLivewire(ViewEventHalls::class);
   }

   //Individual View - House
   public function test_house_single_view_component_is_visible()
   {
       Permission::findOrCreate('house-view');

       $user = User::factory()->create();
       $user->givePermissionTo('house-view');

       $property = Property::factory()->create(); 

       $this->actingAs($user)
           ->get(route('admin.view-property', $property))
           ->assertSeeLivewire(ViewProperty::class);
   }

   //Individual View - Room
   public function test_room_single_view_component_is_visible()
   {
       Permission::findOrCreate('room-view');

       $user = User::factory()->create();
       $user->givePermissionTo('room-view');

       $room = Property::factory()->create(); 

       $this->actingAs($user)
           ->get(route('admin.view-room', $room))
           ->assertSeeLivewire(ViewRoom::class);
   }

   //Individual View - Hall
   public function test_hall_single_view_component_is_visible()
   {
       Permission::findOrCreate('event-hall-view');

       $user = User::factory()->create();
       $user->givePermissionTo('event-hall-view');

       $eventHall = Property::factory()->create(); 

       $this->actingAs($user)
           ->get(route('admin.view-room', $eventHall))
           ->assertSeeLivewire(ViewRoom::class);
   }

   //Create View - House
   public function test_house_create_component_is_visible()
   {
       Permission::findOrCreate('house-create');

       $user = User::factory()->create();
       $user->givePermissionTo('house-create');

       $this->actingAs($user)
           ->get(route('admin.create-property'))
           ->assertSeeLivewire(CreateProperty::class);
   }

   //Create View - Room
   public function test_room_create_component_is_visible()
   {
       Permission::findOrCreate('room-create');

       $user = User::factory()->create();
       $user->givePermissionTo('room-create');

       $this->actingAs($user)
           ->get(route('admin.create-room'))
           ->assertSeeLivewire(CreateRoom::class);
   }

   //Create View - Hall
   public function test_hall_create_component_is_visible()
   {
       Permission::findOrCreate('event-hall-create');

       $user = User::factory()->create();
       $user->givePermissionTo('event-hall-create');

       $this->actingAs($user)
           ->get(route('admin.create-event-hall'))
           ->assertSeeLivewire(CreateEventHall::class);
   }

   //Edit View - House
   public function test_house_edit_component_is_visible()
   {
      Permission::findOrCreate('house-edit');

      $user = User::factory()->create();
      $user->givePermissionTo('house-edit');

       $property = Property::factory()->create();

       // Act as the user and visit the edit route 
       $this->actingAs($user)
       ->get(route('admin.edit-property', $property))
       ->assertSeeLivewire(EditProperty::class);
   }

   //Edit View - Room
   public function test_room_edit_component_is_visible()
   {
      Permission::findOrCreate('room-edit');

      $user = User::factory()->create();
      $user->givePermissionTo('room-edit');

       $room = Property::factory()->create();

       // Act as the user and visit the edit route 
       $this->actingAs($user)
       ->get(route('admin.edit-room', $room))
       ->assertSeeLivewire(EditRoom::class);
   }

   //Edit View - Hall
   public function test_hall_edit_component_is_visible()
   {
      Permission::findOrCreate('event-hall-edit');

      $user = User::factory()->create();
      $user->givePermissionTo('event-hall-edit');

       $eventHall = Property::factory()->create();

       // Act as the user and visit the edit route 
       $this->actingAs($user)
       ->get(route('admin.edit-event-hall', $eventHall))
       ->assertSeeLivewire(EditEventHall::class);
   }

   //Soft Delete View - House
   public function test_house_soft_delete_component_is_visible()
   {
       Permission::findOrCreate('house-soft-delete');

       $user = User::factory()->create();
       $user->givePermissionTo('house-soft-delete');

       $this->actingAs($user)
           ->get(route('admin.deleted-properties'))
           ->assertSeeLivewire(DeletedProperties::class);
   }

   //Soft Delete View - Hall
   public function test_hall_soft_delete_component_is_visible()
   {
       Permission::findOrCreate('event-hall-soft-delete');

       $user = User::factory()->create();
       $user->givePermissionTo('event-hall-soft-delete');

       $this->actingAs($user)
           ->get(route('admin.deleted-event-halls'))
           ->assertSeeLivewire(DeletedEventHalls::class);
   }

   //------------- TESTS TO SEE IF ROUTES ARE VISIBLE WITHOUT PERMISSION //

    //List View - House
    public function test_house_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('house-list');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.properties'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ViewProperties::class);
    }

    //List View - Room
    public function test_room_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('room-list');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.rooms'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ViewRooms::class);
    }

    //List View - Hall
    public function test_hall_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('event-hall-list');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.event-halls'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ViewEventHalls::class);
    }

    //Single View - House
    public function test_house_single_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('house-view');

        //user without permission
        $user = User::factory()->create();

        $property = Property::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.view-property', $property));

        $response->assertForbidden(); 


        $response->assertDontSeeLivewire(ViewProperty::class);
    }

    //Single View - Room
    public function test_room_single_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('room-view');

        //user without permission
        $user = User::factory()->create();

        $room = Property::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.view-room', $room));

        $response->assertForbidden(); 


        $response->assertDontSeeLivewire(ViewRoom::class);
    }

    //Single View - Hall
    public function test_hall_single_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('event-hall-view');

        //user without permission
        $user = User::factory()->create();

        $eventHall = Property::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.view-event-hall', $eventHall));

        $response->assertForbidden(); 
        $response->assertDontSeeLivewire(ViewEventHall::class);
    }


    //Create View - House
    public function test_house_create_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('house-create');


        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.create-property'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(CreateProperty::class);
    }

    //Single View - Room
    public function test_room_create_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('room-create');


        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.create-room'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(CreateRoom::class);
    }

    //Single View - Hall
    public function test_hall_create_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('event-hall-create');


        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.create-event-hall'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(CreateEventHall::class);
    }

    //Edit View - House
    public function test_house_edit_component_is_not_visible_without_permission()
    {
        
        Permission::findOrCreate('house-edit');

        $property = Property::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.edit-property', $property));

        $response->assertForbidden();
    }

    //Edit View - Room
    public function test_room_edit_component_is_not_visible_without_permission()
    {
        
        Permission::findOrCreate('room-edit');

        $room = Property::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.edit-room', $room));

        $response->assertForbidden();
    }

    //Edit View - Hall
    public function test_hall_edit_component_is_not_visible_without_permission()
    {
        
        Permission::findOrCreate('event-hall-edit');

        $eventHall = Property::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.edit-event-hall', $eventHall));

        $response->assertForbidden();
    }

    //Soft Delete View - House
    public function test_house_soft_delete_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('house-soft-delete');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.deleted-properties'));

        // Assert forbidden or redirect
        $response->assertForbidden(); 

        //Ensure the Livewire component is NOT rendered
        $response->assertDontSeeLivewire(DeletedProperties::class);
    }

    //Soft Delete View - Room
    public function test_room_soft_delete_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('room-soft-delete');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.deleted-rooms'));

        // Assert forbidden or redirect
        $response->assertForbidden(); 

        //Ensure the Livewire component is NOT rendered
        $response->assertDontSeeLivewire(DeletedRooms::class);
    }

    //Soft Delete View - Hall
    public function test_hall_soft_delete_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('event-hall-soft-delete');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.deleted-event-halls'));

        // Assert forbidden or redirect
        $response->assertForbidden(); 

        //Ensure the Livewire component is NOT rendered
        $response->assertDontSeeLivewire(DeletedEventHalls::class);
    }



    //-------------------------------- CRUD TESTING //
    //-------------------------------- CREATE //
    //Create House - with no image upload
    public function test_house_can_be_created()
    {
       $user = User::factory()->create();
        $user->givePermissionTo('house-create'); 

    $propertyData = Property::factory()->make()->toArray();

    $allowedProperties = [
        'property_type_id',  // required
        'name_number',       // required, unique
        'property_status',   // required
        'amount',            // required
        'description',       // nullable
        'house_number',      // required
        'street',            // required
        'barangay',          // required
        'city_municipality', // required
        'region',            // required
        'postal_code',       // required
        'country',           // required
    ];

    $livewire = Livewire::actingAs($user)
        ->test(CreateProperty::class);

    // Set only allowed properties (filter out others like ideal_guest, capacity, etc.)
    foreach ($propertyData as $key => $value) {
        if (in_array($key, $allowedProperties)) {
            $livewire->set($key, $value);
        }
    }

    $livewire
        ->call('saveProperty');

    $this->assertDatabaseHas('properties', [
        'name_number' => $propertyData['name_number'],
    ]);
    }


    //------------- Create Room - with no image upload
    public function test_room_can_be_created()
    {
    // Create a user with the required permission
    $user = User::factory()->create();
    $user->givePermissionTo('room-create');

    // Prepare fake data
    $propertyCategory = \App\Models\PropertyCategory::factory()->create();
    $propertyType = 1;

    $roomData = [
        'name_number' => 'Room-' . fake()->unique()->numberBetween(100, 999),
        'property_category_id' => $propertyCategory->id,
        'property_type_id' => $propertyType,
        'ideal_guest' => 3,
        'max_adults' => 2,
        'max_kids' => 2,
        'turnover_duration' => 2,
        'property_status' => 'available',
        'amount' => 1500,
        'extra_person_charge' => 300,
        'image' => null,
        'images' => [],
        'selectedFeatures' => [],
    ];

    // Run the Livewire test
    Livewire::actingAs($user)
        ->test(\App\Livewire\Admin\Rooms\CreateRoom::class) // Adjust component path if needed
        ->set('name_number', $roomData['name_number'])
        ->set('property_category_id', $roomData['property_category_id'])
        ->set('property_type_id', $roomData['property_type_id'])
        ->set('ideal_guest', $roomData['ideal_guest'])
        ->set('max_adults', $roomData['max_adults'])
        ->set('max_kids', $roomData['max_kids'])
        ->set('turnover_duration', $roomData['turnover_duration'])
        ->set('property_status', $roomData['property_status'])
        ->set('amount', $roomData['amount'])
        ->set('extra_person_charge', $roomData['extra_person_charge'])
        ->set('image', null)
        ->set('images', [])
        ->set('selectedFeatures', [])
        ->call('saveRoom')
        ->assertHasNoErrors();

    // Assert that the new room exists in the database
    $this->assertDatabaseHas('properties', [
        'name_number' => $roomData['name_number'],
        'property_type_id' => $roomData['property_type_id'],
        'property_category_id' => $roomData['property_category_id'],
    ]);
    }

    //------------- Create Hall - with no image upload
    //Create House - with no image upload
    public function test_hall_can_be_created()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('event-hall-create');

       $name = 'Hall-' . fake()->unique()->numerify('###');

    $livewire = Livewire::actingAs($user)
        ->test(CreateEventHall::class)
        ->set('name_number', $name)
        ->set('property_type_id', 3)
        ->set('amount', 20000)
        ->set('capacity', 100)
        ->set('extra_charge_per_hour', 1500)
        ->set('description', 'A great hall')
        ->set('property_status', 'available')
        ->set('image', null)
        ->set('images', [])
        ->set('selectedFeatures', []);

    $livewire->call('saveEventHall')->assertHasNoErrors();

    $this->assertDatabaseHas('properties', [
        'name_number' => $name,
    ]);
    }





    //-------------------------------- EDIT //
    //Edit House - with no imageupload
    public function test_house_can_be_updated()
    {
       $user = User::factory()->create();
    $user->givePermissionTo('house-edit');

    $property = Property::factory()->create();

    // Generate fake data for the fields that are actually being updated
    $updatedData = [
        'name_number' => 'Property 17212',
        'property_status' => 'available',
        'amount' => 1500.00,
        'house_number' => '456B',
        'street' => 'New Street',
        'barangay' => 'Barangay Uno',
        'city_municipality' => 'Citysville',
        'region' => 'Region IV-A',
        'postal_code' => '4321',
        'country' => 'Philippines',
        'description' => 'Updated description for the property.',
    ];

    Livewire::actingAs($user)
        ->test(EditProperty::class, ['property' => $property])
        ->set('name_number', $updatedData['name_number'])
        ->set('property_status', $updatedData['property_status'])
        ->set('amount', $updatedData['amount'])
        ->set('house_number', $updatedData['house_number'])
        ->set('street', $updatedData['street'])
        ->set('barangay', $updatedData['barangay'])
        ->set('city_municipality', $updatedData['city_municipality'])
        ->set('region', $updatedData['region'])
        ->set('postal_code', $updatedData['postal_code'])
        ->set('country', $updatedData['country'])
        ->set('description', $updatedData['description'])
        ->call('updateProperty')
        ->assertHasNoErrors();

    // Confirm database was updated
    $this->assertDatabaseHas('properties', [
        'id' => $property->id,
        'name_number' => $updatedData['name_number'],
    ]);
    }

    //Edit Room
    public function test_room_can_be_updated()
    {
        $user = User::factory()->create();
    $user->givePermissionTo('room-edit');

    $room = Property::factory()->create([
        'property_type_id' => 1, 
        'name_number' => 'Room-109',
    ]);

    $updatedData = [
        'name_number' => 'Room 19812',
        'property_category_id' => null, // optional
        'ideal_guest' => 4,
        'max_adults' => 2,
        'max_kids' => 2,
        'turnover_duration' => 2,
        'property_status' => 'available',
        'occupancy_rules' => [
            ['adults' => 2, 'kids' => 2],
            ['adults' => 1, 'kids' => 1],
        ],
        'amount' => 1500.00,
        'extra_person_charge' => 200.00,
        'storedImages' => [],
        'newImages' => [],
        'selectedFeatures' => [], // optional
    ];

    Livewire::actingAs($user)
        ->test(EditRoom::class, ['room' => $room])
        ->set('name_number', $updatedData['name_number'])
        ->set('property_category_id', $updatedData['property_category_id'])
        ->set('ideal_guest', $updatedData['ideal_guest'])
        ->set('max_adults', $updatedData['max_adults'])
        ->set('max_kids', $updatedData['max_kids'])
        ->set('turnover_duration', $updatedData['turnover_duration'])
        ->set('property_status', $updatedData['property_status'])
        ->set('amount', $updatedData['amount'])
        ->set('occupancy_rules', $updatedData['occupancy_rules'])
        ->set('extra_person_charge', $updatedData['extra_person_charge'])
        ->set('storedImages', $updatedData['storedImages'])
        ->set('newImages', $updatedData['newImages'])
        ->set('selectedFeatures', $updatedData['selectedFeatures'])
        ->call('updateRoom')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.rooms'));

    $this->assertDatabaseHas('properties', [
        'id' => $room->id,
        'name_number' => $updatedData['name_number'],
    ]);
    }

    


    //Edit Hall - with no imageupload
    public function test_hall_can_be_updated()
    {
       $user = User::factory()->create();
    $user->givePermissionTo('event-hall-edit');

    $eventHall = Property::factory()->create([
        'property_type_id' => 3,
        'name_number' => 'Hall-Alpha',
        'amount' => 30000,
        'capacity' => 50,
        'extra_charge_per_hour' => 2000,
        'property_status' => 'available',
    ]);

    $updatedData = [
        'name_number' => 'Hall 1001',
        'description' => 'Spacious hall for large gatherings.',
        'amount' => 45000,
        'capacity' => 100,
        'extra_charge_per_hour' => 3500,
        'property_status' => 'booked',
        'storedImages' => [],
        'newImages' => [],
        'selectedFeatures' => [],
    ];

    Livewire::actingAs($user)
        ->test(EditEventHall::class, ['eventHall' => $eventHall])
        ->set('name_number', $updatedData['name_number'])
        ->set('description', $updatedData['description'])
        ->set('amount', $updatedData['amount'])
        ->set('capacity', $updatedData['capacity'])
        ->set('extra_charge_per_hour', $updatedData['extra_charge_per_hour'])
        ->set('property_status', $updatedData['property_status'])
        ->set('storedImages', $updatedData['storedImages'])
        ->set('newImages', $updatedData['newImages'])
        ->set('selectedFeatures', $updatedData['selectedFeatures'])
        ->call('updateEventHall')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.event-halls'));

    $this->assertDatabaseHas('properties', [
        'id' => $eventHall->id,
        'name_number' => $updatedData['name_number'],
        'description' => $updatedData['description'],
        'amount' => $updatedData['amount'],
        'capacity' => $updatedData['capacity'],
        'extra_charge_per_hour' => $updatedData['extra_charge_per_hour'],
        'property_status' => $updatedData['property_status'],
    ]);
    }



    //-------------------------------- SOFT DELETES //

    //Soft Deletes House View
    public function test_house_can_be_soft_deleted()
    {
        $property = Property::factory()->create();

        Livewire::test(ViewProperty::class, ['property' => $property])
            ->set('confirmItemDelete', $property->id)
            ->call('deleteHouse')
            ->assertHasNoErrors();

        $this->assertSoftDeleted('properties', ['id' => $property->id]);
    }

    //Soft Delete Room View
    public function test_room_can_be_soft_deleted()
    {
        $room = Property::factory()->create();

        Livewire::test(ViewRoom::class, ['room' => $room])
            ->set('confirmItemDelete', $room->id)
            ->call('deleteRoom')
            ->assertHasNoErrors();

        $this->assertSoftDeleted('properties', ['id' => $room->id]);
    }

    //Soft Delete Hall View
    public function test_hall_can_be_soft_deleted()
    {
        $eventHall = Property::factory()->create();

        Livewire::test(ViewEventHall::class, ['eventHall' => $eventHall])
            ->set('confirmItemDelete', $eventHall->id)
            ->call('deleteEventHall')
            ->assertHasNoErrors();

        $this->assertSoftDeleted('properties', ['id' => $eventHall->id]);
    }




    //-------------------------------- SOFT DELETES IN LIST//
    //House
    public function test_house_can_be_soft_deleted_in_list()
    {
        $property = Property::factory()->create();

        $user = User::factory()->create();
        $user->givePermissionTo('house-soft-delete');
    
        Livewire::actingAs($user)
            ->test(ViewProperties::class) 
            ->set('confirmItemDelete', $property->id)
            ->call('deleteHouse') 
            ->assertHasNoErrors();
    
        // Check if it was soft-deleted in db
        $this->assertSoftDeleted('properties', ['id' => $property->id]);
    }

    //Room
    public function test_room_can_be_soft_deleted_in_list()
    {
        $room = Property::factory()->create();

        $user = User::factory()->create();
        $user->givePermissionTo('room-soft-delete');
    
        Livewire::actingAs($user)
            ->test(ViewRooms::class) 
            ->set('confirmItemDelete', $room->id)
            ->call('deleteRoom') 
            ->assertHasNoErrors();
    
        // Check if it was soft-deleted in db
        $this->assertSoftDeleted('properties', ['id' => $room->id]);
    }

    //Hall
    public function test_hall_can_be_soft_deleted_in_list()
    {
        $eventHall = Property::factory()->create();

        $user = User::factory()->create();
        $user->givePermissionTo('event-hall-soft-delete');
    
        Livewire::actingAs($user)
            ->test(ViewEventHalls::class) 
            ->set('confirmItemDelete', $eventHall->id)
            ->call('deleteEventHall') 
            ->assertHasNoErrors();
    
        // Check if it was soft-deleted in db
        $this->assertSoftDeleted('properties', ['id' => $eventHall->id]);
    }





    //----------------------------- PERMANENT DELETES
    //House
    public function test_house_can_be_permanently_deleted(){
         //creating a record then soft deleting
         $property = Property::factory()->create();
         $property->delete();
 
     //set id
     Livewire::test(DeletedProperties::class)
         ->set('confirmItemDelete', $property->id) //make modal true
         ->call('deletePropertyForever', $property->id) //passes the id
         ->assertHasNoErrors();
 
     // Assert the maintenance record was permanently deleted
         $this->assertDatabaseMissing('properties', [
             'id' => $property->id,
     ]);
 
    }

    //Room
    public function test_room_can_be_permanently_deleted(){
         //creating a record then soft deleting
         $room = Property::factory()->create();
         $room->delete();
 
     //set id
     Livewire::test(DeletedRooms::class)
         ->set('confirmItemDelete', $room->id) //make modal true
         ->call('deleteRoomForever', $room->id) //passes the id
         ->assertHasNoErrors();
 
     // Assert the maintenance record was permanently deleted
         $this->assertDatabaseMissing('properties', [
             'id' => $room->id,
     ]);
 
    }

    //Hall
    public function test_hall_can_be_permanently_deleted(){
         //creating a record then soft deleting
         $eventHall = Property::factory()->create();
         $eventHall->delete();
 
     //set id
     Livewire::test(DeletedEventHalls::class)
         ->set('confirmItemDelete', $eventHall->id) //make modal true
         ->call('deleteEventHallForever', $eventHall->id) //passes the id
         ->assertHasNoErrors();
 
     // Assert the maintenance record was permanently deleted
         $this->assertDatabaseMissing('properties', [
            'id' => $eventHall->id,
     ]);
 
    }

    // ------------------------------ CANNOT DELETES
    //House
    public function test_property_cannot_be_deleted_if_used_in_transactions()
    {
    // Create an property
    $property = Property::factory()->create();

    // Create a transaction user (creator)
    $user = TransactionUser::factory()->create();

    // Create a transaction and attach the activity to it
    $transaction = Transaction::factory()->create([
        'created_by' => $user->id,
    ]);

    // Attach activity to transaction (using pivot table 'transaction_activities')
    $transaction->properties()->attach($property->id, [
        'adults' => 2,
        'kids' => 1,
        'extra_guest' => 1,
        'extra_charge' => 1000,
        'amount' => 5000, 
        'total_amount' => 5000, 
        'days' => 10, 
    ]);

    // Mock the Livewire component and set confirmItemDelete to the activity id
    //in Individual Delete
    Livewire::test(ViewProperty::class, ['property' => $property])
        ->set('confirmItemDelete', $property->id)
        ->call('deleteHouse')
        ->assertSet('cannotDeleteItem', true)
        ->assertHasNoErrors();

    //in List Delete
    Livewire::test(ViewProperties::class, ['property' => $property])
        ->set('confirmItemDelete', $property->id)
        ->call('deleteHouse')
        ->assertSet('cannotDeleteItem', true)
        ->assertHasNoErrors();

        // Simulate bulk delete attempt 
    Livewire::test(ViewProperties::class)
        ->set('selectedRows', [$property->id])
        ->set('confirmBulkDelete', true)
        ->call('deleteSelectedRows')
        ->assertSet('cannotDeleteItem', true)
        ->assertSet('confirmBulkDelete', false)
        ->assertHasNoErrors();

    // Assert activity still exists in database (not soft deleted)
    $this->assertDatabaseHas('properties', [
        'id' => $property->id,
        'deleted_at' => null,
    ]);
    }


    //Room
    public function test_room_cannot_be_deleted_if_used_in_transactions()
    {
    // Create an property
    $room = Property::factory()->create();

    // Create a transaction user (creator)
    $user = TransactionUser::factory()->create();

    // Create a transaction and attach the activity to it
    $transaction = Transaction::factory()->create([
        'created_by' => $user->id,
    ]);

    // Attach activity to transaction (using pivot table 'transaction_activities')
    $transaction->properties()->attach($room->id, [
        'adults' => 2,
        'kids' => 1,
        'extra_guest' => 1,
        'extra_charge' => 1000,
        'amount' => 5000, 
        'total_amount' => 5000, 
        'days' => 10, 
    ]);

    // Mock the Livewire component and set confirmItemDelete to the activity id
    //in Individual Delete
    Livewire::test(ViewRoom::class, ['room' => $room])
        ->set('confirmItemDelete', $room->id)
        ->call('deleteRoom')
        ->assertSet('cannotDeleteItem', true)
        ->assertHasNoErrors();

    //in List Delete
    Livewire::test(ViewRooms::class, ['room' => $room])
        ->set('confirmItemDelete', $room->id)
        ->call('deleteRoom')
        ->assertSet('cannotDeleteItem', true)
        ->assertHasNoErrors();

        // Simulate bulk delete attempt 
    Livewire::test(ViewRooms::class)
        ->set('selectedRows', [$room->id])
        ->set('confirmBulkDelete', true)
        ->call('deleteSelectedRows')
        ->assertSet('cannotDeleteItem', true)
        ->assertSet('confirmBulkDelete', false)
        ->assertHasNoErrors();

    // Assert activity still exists in database (not soft deleted)
    $this->assertDatabaseHas('properties', [
        'id' => $room->id,
        'deleted_at' => null,
    ]);
    }

    //Hall
    public function test_hall_cannot_be_deleted_if_used_in_transactions()
    {
    // Create an property
    $eventHall = Property::factory()->create();

    // Create a transaction user (creator)
    $user = TransactionUser::factory()->create();

    // Create a transaction and attach the activity to it
    $transaction = Transaction::factory()->create([
        'created_by' => $user->id,
    ]);

    // Attach activity to transaction (using pivot table 'transaction_activities')
    $transaction->properties()->attach($eventHall->id, [
        'adults' => 2,
        'kids' => 1,
        'extra_guest' => 1,
        'extra_charge' => 1000,
        'amount' => 5000, 
        'total_amount' => 5000, 
        'days' => 10, 
    ]);

    // Mock the Livewire component and set confirmItemDelete to the activity id
    //in Individual Delete
    Livewire::test(ViewEventHall::class, ['eventHall' => $eventHall])
        ->set('confirmItemDelete', $eventHall->id)
        ->call('deleteEventHall')
        ->assertSet('cannotDeleteItem', true)
        ->assertHasNoErrors();

    //in List Delete
    Livewire::test(ViewEventHalls::class, ['eventHall' => $eventHall])
        ->set('confirmItemDelete', $eventHall->id)
        ->call('deleteEventHall')
        ->assertSet('cannotDeleteItem', true)
        ->assertHasNoErrors();

        // Simulate bulk delete attempt 
    Livewire::test(ViewEventHalls::class)
        ->set('selectedRows', [$eventHall->id])
        ->set('confirmBulkDelete', true)
        ->call('deleteSelectedRows')
        ->assertSet('cannotDeleteItem', true)
        ->assertSet('confirmBulkDelete', false)
        ->assertHasNoErrors();

    // Assert activity still exists in database (not soft deleted)
    $this->assertDatabaseHas('properties', [
        'id' => $eventHall->id,
        'deleted_at' => null,
    ]);
    }


    // ---------------------------- BULK DELETES
    //House
    public function test_bulk_delete_properties_successfully()
    {
    $properties = Property::factory()->count(3)->create();

    $propertyIds = $properties->pluck('id')->toArray();

    // Confirm all exist before deletion
    foreach ($propertyIds as $id) {
        $this->assertDatabaseHas('properties', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }

    // Attempt bulk delete using list component
    Livewire::test(ViewProperties::class)
        ->set('selectedRows', $propertyIds)
        ->set('confirmBulkDelete', true)
        ->call('deleteSelectedRows')
        ->assertSet('confirmBulkDelete', false)
        ->assertHasNoErrors();

    // Confirm all are soft-deleted
    foreach ($propertyIds as $id) {
        $this->assertSoftDeleted('properties', [
            'id' => $id,
        ]);
    }
    }

    //Room
    public function test_bulk_delete_rooms_successfully()
    {
    $rooms = Property::factory()->count(3)->create();

    $roomIds = $rooms->pluck('id')->toArray();

    // Confirm all exist before deletion
    foreach ($roomIds as $id) {
        $this->assertDatabaseHas('properties', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }

    // Attempt bulk delete using list component
    Livewire::test(ViewRooms::class)
        ->set('selectedRows', $roomIds)
        ->set('confirmBulkDelete', true)
        ->call('deleteSelectedRows')
        ->assertSet('confirmBulkDelete', false)
        ->assertHasNoErrors();

    // Confirm all are soft-deleted
    foreach ($roomIds as $id) {
        $this->assertSoftDeleted('properties', [
            'id' => $id,
        ]);
    }
    }

    //Hall
    public function test_bulk_delete_halls_successfully()
    {
    $halls = Property::factory()->count(3)->create();

    $hallIds = $halls->pluck('id')->toArray();

    // Confirm all exist before deletion
    foreach ($hallIds as $id) {
        $this->assertDatabaseHas('properties', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }

    // Attempt bulk delete using list component
    Livewire::test(ViewEventHalls::class)
        ->set('selectedRows', $hallIds)
        ->set('confirmBulkDelete', true)
        ->call('deleteSelectedRows')
        ->assertSet('confirmBulkDelete', false)
        ->assertHasNoErrors();

    // Confirm all are soft-deleted
    foreach ($hallIds as $id) {
        $this->assertSoftDeleted('properties', [
            'id' => $id,
        ]);
    }
    }

}
