<?php

namespace Tests\Feature;

use App\Livewire\Admin\Amenities\CreateAmenity;
use App\Livewire\Admin\Amenities\DeletedAmenities;
use App\Livewire\Admin\Amenities\EditAmenity;
use App\Livewire\Admin\Amenities\ViewAmenities;
use App\Livewire\Admin\Amenities\ViewAmenity;
use App\Models\Amenity;
use App\Models\PropertyFeature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PropertyFeatureTest extends TestCase
{
    //Factory for Amenities, Features, and Inclusions

    //----------------------- AMENITIES
    //----------------TESTS TO SEE IF ROUTES ARE VISIBLE WITH PERMISSION
    //List View
    public function test_amenities_view_component_is_visible()
    {
        Permission::findOrCreate('amenity-list');

        $user = User::factory()->create();
        $user->givePermissionTo('amenity-list');

        $this->actingAs($user)
            ->get(route('admin.amenities'))
            ->assertSeeLivewire(ViewAmenities::class);
    }

    //Individual View
    public function test_amenities_single_view_component_is_visible()
    {
        Permission::findOrCreate('amenity-view');

        $user = User::factory()->create();
        $user->givePermissionTo('amenity-view');

        $amenity = PropertyFeature::factory()->create(); 

        $this->actingAs($user)
           ->get(route('admin.view-amenity', ['amenity' => $amenity->id]))
            ->assertSeeLivewire(ViewAmenity::class);
    }

    //Create View
    public function test_amenity_create_component_is_visible()
    {
        Permission::findOrCreate('amenity-create');

        $user = User::factory()->create();
        $user->givePermissionTo('amenity-create');

        $this->actingAs($user)
            ->get(route('admin.create-amenity'))
            ->assertSeeLivewire(CreateAmenity::class);
    }

    //Edit View
    public function test_amenity_edit_component_is_visible()
    {
       Permission::findOrCreate('amenity-edit');

       $user = User::factory()->create();
       $user->givePermissionTo('amenity-edit');

        $amenity = PropertyFeature::factory()->create();

        // Act as the user and visit the edit route 
        $this->actingAs($user)
        ->get(route('admin.edit-amenity', ['amenity' => $amenity->id]))
        ->assertSeeLivewire(EditAmenity::class);
    }

    //Soft Delete View
    public function test_amenity_soft_delete_component_is_visible()
    {
        Permission::findOrCreate('amenity-soft-delete');

        $user = User::factory()->create();
        $user->givePermissionTo('amenity-soft-delete');

        $this->actingAs($user)
            ->get(route('admin.deleted-amenities'))
            ->assertSeeLivewire(DeletedAmenities::class);
    }

    //TESTS TO SEE IF ROUTES ARE VISIBLE WITHOUT PERMISSION
    //List View
    public function test_amenities_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('amenity-list');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.amenities'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ViewAmenities::class);
    }

    //Single View
    public function test_amenity_single_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('amenity-view');

        //user without permission
        $user = User::factory()->create();

        $amenity = PropertyFeature::factory()->create();

        $response = $this->actingAs($user)
        ->get(route('admin.view-amenity', $amenity->id));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ViewAmenity::class);
    }

    //Create View
    public function test_amenity_create_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('amenity-create');

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.create-amenity'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(CreateAmenity::class);
    }

    //Edit View
    public function test_amenity_edit_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('amenity-edit');

        $amenity = PropertyFeature::factory()->create();
        $user = User::factory()->create();

         $response = $this->actingAs($user)
        ->get(route('admin.edit-amenity', $amenity->id));

        $response->assertForbidden();
    }

    //Soft Delete View
    public function test_amenity_soft_delete_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('amenity-soft-delete');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.deleted-amenities'));

        // Assert forbidden or redirect
        $response->assertForbidden(); 

        //Ensure the Livewire component is NOT rendered
        $response->assertDontSeeLivewire(DeletedAmenities::class);
    }

    // -------------------------- CRUD TESTING
    //Create - with no image upload
    public function test_amenity_can_be_created()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('amenity-create'); 

        // Generate only fields that the component actually accepts
        $amenityData = PropertyFeature::factory()->make([
            'property_type_id' => 1, //For amenities
        ])->toArray();

        $livewire = Livewire::actingAs($user)
            ->test(CreateAmenity::class);

        $livewire->set('name', $amenityData['name']);

        $livewire->call('saveAmenity');

        $this->assertDatabaseHas('property_features', [
            'name' => $amenityData['name'],
            'property_type_id' => 1,
        ]);
    }

    //Edit 
    public function test_amenity_can_be_updated()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('amenity-edit');
    
        $amenity = PropertyFeature::factory()->create([
        'property_type_id' => 1,
    ]);
    
        // Create a set of new (fake) updated data
        $updatedData = PropertyFeature::factory()->make()->toArray();
    
        $livewire = Livewire::actingAs($user)
            ->test(EditAmenity::class, ['amenity' => $amenity]);
    
        foreach ($updatedData as $key => $value) {
            $livewire->set($key, $value);
        }
    
        $livewire
            ->call('updateAmenity') 
            ->assertSessionHas('message', 'Amenity successfully updated!')
            ->assertRedirect(route('admin.amenities'));
    
        $this->assertDatabaseHas('property_features', [
            'id' => $amenity->id,
            'name' => $updatedData['name'],
        ]);
    }

    //Soft Delete
    public function test_amenity_can_be_soft_deleted()
    {
        $amenity = PropertyFeature::factory()->create();

        Livewire::test(ViewAmenity::class, ['amenity' => $amenity])
            ->set('confirmItemDelete',  $amenity->id) 
            ->call('deleteAmenity')
            ->assertHasNoErrors();

        $this->assertSoftDeleted('property_features', ['id' => $amenity->id]);
    }

    //Soft Delete In List
    public function test_amenities_can_be_soft_deleted_in_list()
    {
        $amenity = PropertyFeature::factory()->create();

        $user = User::factory()->create();
        $user->givePermissionTo('amenity-soft-delete');
    
        Livewire::actingAs($user)
            ->test(ViewAmenities::class) 
            ->set('confirmItemDelete', $amenity->id)
            ->call('deleteAmenity', $amenity->id) 
            ->assertHasNoErrors();

        $this->assertSoftDeleted('property_features', ['id' => $amenity->id]);
    }

    //Permanently Deleted
    public function test_amenity_can_be_permanently_deleted()
    {
        //creating a record then soft deleting
        $amenity = PropertyFeature::factory()->create();
        $amenity->delete();
 
     //set id
     Livewire::test(DeletedAmenities::class)
         ->set('confirmItemDelete', $amenity->id) //make modal true
         ->call('deleteAmenityForever', $amenity->id) //passes the id
         ->assertHasNoErrors();
 
     // Assert the payment record was permanently deleted
        $this->assertDatabaseMissing('property_features', [
            'id' => $amenity->id,
     ]);
    }

    //FEATURES
}
