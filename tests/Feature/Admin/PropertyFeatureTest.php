<?php

namespace Tests\Feature;

use App\Livewire\Admin\Amenities\CreateAmenity;
use App\Livewire\Admin\Amenities\DeletedAmenities;
use App\Livewire\Admin\Amenities\EditAmenity;
use App\Livewire\Admin\Amenities\ViewAmenities;
use App\Livewire\Admin\Amenities\ViewAmenity;
use App\Livewire\Admin\Features\CreateFeature;
use App\Livewire\Admin\Features\DeletedFeatures;
use App\Livewire\Admin\Features\EditFeature;
use App\Livewire\Admin\Features\ViewFeature;
use App\Livewire\Admin\Features\ViewFeatures;
use App\Livewire\Admin\Inclusions\CreateInclusion;
use App\Livewire\Admin\Inclusions\DeletedInclusions;
use App\Livewire\Admin\Inclusions\EditInclusion;
use App\Livewire\Admin\Inclusions\ViewInclusion;
use App\Livewire\Admin\Inclusions\ViewInclusions;
use App\Models\Amenity;
use App\Models\PropertyFeature;
use App\Models\User;
use Database\Factories\PropertyFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PropertyFeatureTest extends TestCase
{
    //Factory for Amenities, Features, and Inclusions

    //------------------------------------------ AMENITIES
    //---------------------------- TESTS TO SEE IF ROUTES ARE VISIBLE WITH PERMISSION
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

    //---------------------- TESTS TO SEE IF ROUTES ARE VISIBLE WITHOUT PERMISSION
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

    // -------------------------------------- CRUD TESTING -----------------------------//
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

    //Bulk Deletes
    public function test_bulk_delete_amenities_successfully()
    {
    // Create amenity
    $amenities = PropertyFeature::factory()
        ->count(3)
        ->create([
            'property_type_id' => 1, //create an amenity
        ]);

    $amenityIds = $amenities->pluck('id')->toArray();

    // Confirm all exist before deletion
    foreach ($amenityIds as $id) {
        $this->assertDatabaseHas('property_features', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }

    // Attempt bulk delete using View component
    Livewire::test(ViewAmenities::class)
        ->set('selectedRows', $amenityIds)
        ->set('confirmBulkDelete', true)
        ->call('deleteSelectedRows')
        ->assertSet('confirmBulkDelete', false)
        ->assertHasNoErrors();


    // Confirm all are soft-deleted
    foreach ($amenityIds as $id) {
        $this->assertSoftDeleted('property_features', [
            'id' => $id,
        ]);
    }
    }


    //---------------------------- FEATURES ---------------------------------------//
    //---------------------------- TESTS TO SEE IF ROUTES ARE VISIBLE WITH PERMISSION
    //List View
    public function test_features_view_component_is_visible()
    {
        Permission::findOrCreate('house-features-list');

        $user = User::factory()->create();
        $user->givePermissionTo('house-features-list');

        $this->actingAs($user)
            ->get(route('admin.features'))
            ->assertSeeLivewire(ViewFeatures::class);
    }

    //Individual View
    public function test_features_single_view_component_is_visible()
    {
        Permission::findOrCreate('house-features-view');

        $user = User::factory()->create();
        $user->givePermissionTo('house-features-view');

        $feature = PropertyFeature::factory()->create(); 

        $this->actingAs($user)
           ->get(route('admin.view-feature', ['feature' => $feature->id]))
            ->assertSeeLivewire(ViewFeature::class);
    }

    //Create View
    public function test_features_create_component_is_visible()
    {
        Permission::findOrCreate('house-features-create');

        $user = User::factory()->create();
        $user->givePermissionTo('house-features-create');

        $this->actingAs($user)
            ->get(route('admin.create-feature'))
            ->assertSeeLivewire(CreateFeature::class);
    }

    //Edit View
    public function test_features_edit_component_is_visible()
    {
       Permission::findOrCreate('house-features-edit');

       $user = User::factory()->create();
       $user->givePermissionTo('house-features-edit');

        $feature = PropertyFeature::factory()->create();

        // Act as the user and visit the edit route 
        $this->actingAs($user)
        ->get(route('admin.edit-feature', ['feature' => $feature->id]))
        ->assertSeeLivewire(EditFeature::class);
    }

    //Soft Delete View
    public function test_features_soft_delete_component_is_visible()
    {
        Permission::findOrCreate('house-features-soft-delete');

        $user = User::factory()->create();
        $user->givePermissionTo('house-features-soft-delete');

        $this->actingAs($user)
            ->get(route('admin.deleted-features'))
            ->assertSeeLivewire(DeletedFeatures::class);
    }


    //---------------------- TESTS TO SEE IF ROUTES ARE VISIBLE WITHOUT PERMISSION
    //List View
    public function test_features_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('house-features-list');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.features'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ViewFeatures::class);
    }

    //Single View
    public function test_features_single_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('house-features-view');

        //user without permission
        $user = User::factory()->create();

        $feature = PropertyFeature::factory()->create();

        $response = $this->actingAs($user)
        ->get(route('admin.view-feature', $feature->id));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ViewFeature::class);
    }

    //Create View
    public function test_features_create_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('house-features-create');

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.create-feature'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(CreateFeature::class);
    }

    //Edit View
    public function test_feature_edit_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('house-features-edit');

        $feature = PropertyFeature::factory()->create();
        $user = User::factory()->create();

         $response = $this->actingAs($user)
        ->get(route('admin.edit-feature', $feature->id));

        $response->assertForbidden();
    }

    //Soft Delete View
    public function test_feature_soft_delete_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('house-features-soft-delete');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.deleted-features'));

        // Assert forbidden or redirect
        $response->assertForbidden(); 

        //Ensure the Livewire component is NOT rendered
        $response->assertDontSeeLivewire(DeletedFeatures::class);
    }

    // -------------------------------------- CRUD TESTING -----------------------------//
    //Create - with no image upload
    public function test_feature_can_be_created()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('house-features-create'); 

        // Generate only fields that the component actually accepts
        $featureData = PropertyFeature::factory()->make([
            'property_type_id' => 2, //For features
        ])->toArray();

        $livewire = Livewire::actingAs($user)
            ->test(CreateFeature::class);

        $livewire->set('name', $featureData['name']);

        $livewire->call('saveFeature');

        $this->assertDatabaseHas('property_features', [
            'name' => $featureData['name'],
            'property_type_id' => 2, //house features
        ]);
    }

    //Edit 
    public function test_feature_can_be_updated()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('house-features-edit');
    
        $feature = PropertyFeature::factory()->create([
        'property_type_id' => 2,
    ]);
    
        // Create a set of new (fake) updated data
        $updatedData = PropertyFeature::factory()->make()->toArray();
    
        $livewire = Livewire::actingAs($user)
            ->test(EditFeature::class, ['feature' => $feature]);
    
        foreach ($updatedData as $key => $value) {
            $livewire->set($key, $value);
        }
    
        $livewire
            ->call('updateFeature') 
            ->assertSessionHas('message', 'Feature successfully updated!')
            ->assertRedirect(route('admin.features'));
    
        $this->assertDatabaseHas('property_features', [
            'id' => $feature->id,
            'name' => $updatedData['name'],
        ]);
    }

    //Soft Delete
    public function test_feature_can_be_soft_deleted()
    {
        $feature = PropertyFeature::factory()->create();

        Livewire::test(ViewFeature::class, ['feature' => $feature])
            ->set('confirmItemDelete',  $feature->id) 
            ->call('deleteFeature')
            ->assertHasNoErrors();

        $this->assertSoftDeleted('property_features', ['id' => $feature->id]);
    }

    //Soft Delete In List
    public function test_feature_can_be_soft_deleted_in_list()
    {
        $feature = PropertyFeature::factory()->create();

        $user = User::factory()->create();
        $user->givePermissionTo('house-features-soft-delete');
    
        Livewire::actingAs($user)
            ->test(ViewFeatures::class) 
            ->set('confirmItemDelete', $feature->id)
            ->call('deleteFeature', $feature->id) 
            ->assertHasNoErrors();

        $this->assertSoftDeleted('property_features', ['id' => $feature->id]);
    }

    //Permanently Deleted
    public function test_feature_can_be_permanently_deleted()
    {
        //creating a record then soft deleting
        $feature = PropertyFeature::factory()->create();
        $feature->delete();
 
     //set id
     Livewire::test(DeletedFeatures::class)
         ->set('confirmItemDelete', $feature->id) //make modal true
         ->call('deleteFeatureForever', $feature->id) //passes the id
         ->assertHasNoErrors();
 
     // Assert the payment record was permanently deleted
        $this->assertDatabaseMissing('property_features', [
            'id' => $feature->id,
     ]);
    }

    //Bulk Deletes
    public function test_bulk_delete_features_successfully()
    {
    // Create features
    $features = PropertyFeature::factory()
        ->count(3)
        ->create([
            'property_type_id' => 2,
        ]);

    $featureIds = $features->pluck('id')->toArray();

    // Confirm all exist before deletion
    foreach ($featureIds as $id) {
        $this->assertDatabaseHas('property_features', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }

    // Attempt bulk delete using ViewFeatures component
    Livewire::test(ViewFeatures::class)
        ->set('selectedRows', $featureIds)
        ->set('confirmBulkDelete', true)
        ->call('deleteSelectedRows')
        ->assertSet('confirmBulkDelete', false)
        ->assertHasNoErrors();


    // Confirm all are soft-deleted
    foreach ($featureIds as $id) {
        $this->assertSoftDeleted('property_features', [
            'id' => $id,
        ]);
    }
    }


    //----------------------------- INCLUSIONS ------------------------------------ //
    //---------------------------- TESTS TO SEE IF ROUTES ARE VISIBLE WITH PERMISSION -- /
    //List View
    public function test_inclusions_view_component_is_visible()
    {
        Permission::findOrCreate('event-inclusions-list');

        $user = User::factory()->create();
        $user->givePermissionTo('event-inclusions-list');

        $this->actingAs($user)
            ->get(route('admin.inclusions'))
            ->assertSeeLivewire(ViewInclusions::class);
    }

    //Individual View
    public function test_inclusions_single_view_component_is_visible()
    {
        Permission::findOrCreate('event-inclusions-view');

        $user = User::factory()->create();
        $user->givePermissionTo('event-inclusions-view');

        $inclusion = PropertyFeature::factory()->create(); 

        $this->actingAs($user)
           ->get(route('admin.view-inclusion', ['inclusion' => $inclusion->id]))
            ->assertSeeLivewire(ViewInclusion::class);
    }

    //Create View
    public function test_inclusion_create_component_is_visible()
    {
        Permission::findOrCreate('event-inclusions-create');

        $user = User::factory()->create();
        $user->givePermissionTo('event-inclusions-create');

        $this->actingAs($user)
            ->get(route('admin.create-inclusion'))
            ->assertSeeLivewire(CreateInclusion::class);
    }

    //Edit View
    public function test_inclusions_edit_component_is_visible()
    {
       Permission::findOrCreate('event-inclusions-edit');

       $user = User::factory()->create();
       $user->givePermissionTo('event-inclusions-edit');

        $inclusion = PropertyFeature::factory()->create();

        // Act as the user and visit the edit route 
        $this->actingAs($user)
        ->get(route('admin.edit-inclusion', ['inclusion' => $inclusion->id]))
        ->assertSeeLivewire(EditInclusion::class);
    }

    //Soft Delete View
    public function test_inclusion_soft_delete_component_is_visible()
    {
        Permission::findOrCreate('event-inclusions-soft-delete');

        $user = User::factory()->create();
        $user->givePermissionTo('event-inclusions-soft-delete');

        $this->actingAs($user)
            ->get(route('admin.deleted-inclusions'))
            ->assertSeeLivewire(DeletedInclusions::class);
    }

    //---------------------- TESTS TO SEE IF ROUTES ARE VISIBLE WITHOUT PERMISSION
    //List View
    public function test_inclusions_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('event-inclusions-list');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.inclusions'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ViewInclusions::class);
    }

    //Single View
    public function test_inclusions_single_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('event-inclusions-view');

        //user without permission
        $user = User::factory()->create();

        $inclusion = PropertyFeature::factory()->create();

        $response = $this->actingAs($user)
        ->get(route('admin.view-inclusion', $inclusion->id));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ViewInclusion::class);
    }

    //Create View
    public function test_inclusions_create_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('event-inclusions-create');

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.create-inclusion'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(CreateInclusion::class);
    }

    //Edit View
    public function test_inclusion_edit_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('event-inclusions-edit');

        $inclusion = PropertyFeature::factory()->create();
        $user = User::factory()->create();

         $response = $this->actingAs($user)
        ->get(route('admin.edit-inclusion', $inclusion->id));

        $response->assertForbidden();
    }

    //Soft Delete View
    public function test_inclusion_soft_delete_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('event-inclusions-soft-delete');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.deleted-inclusions'));

        // Assert forbidden or redirect
        $response->assertForbidden(); 

        //Ensure the Livewire component is NOT rendered
        $response->assertDontSeeLivewire(DeletedInclusions::class);
    }


    // -------------------------------------- CRUD TESTING -----------------------------//
    //Create - with no image upload
    public function test_inclusion_can_be_created()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('event-inclusions-create'); 

        // Generate only fields that the component actually accepts
        $inclusionData = PropertyFeature::factory()->make([
            'property_type_id' => 3, //For inclusions
        ])->toArray();

        $livewire = Livewire::actingAs($user)
            ->test(CreateInclusion::class);

        $livewire->set('name', $inclusionData['name']);

        $livewire->call('saveInclusion');

        $this->assertDatabaseHas('property_features', [
            'name' => $inclusionData['name'],
            'property_type_id' => 3,
        ]);
    }

    //Edit 
    public function test_inclusion_can_be_updated()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('event-inclusions-edit');
    
        $inclusion = PropertyFeature::factory()->create([
        'property_type_id' => 3,
    ]);
    
        // Create a set of new (fake) updated data
        $updatedData = PropertyFeature::factory()->make()->toArray();
    
        $livewire = Livewire::actingAs($user)
            ->test(EditInclusion::class, ['inclusion' => $inclusion]);
    
        foreach ($updatedData as $key => $value) {
            $livewire->set($key, $value);
        }
    
        $livewire
            ->call('updateInclusion') 
            ->assertSessionHas('message', 'Inclusion successfully updated!')
            ->assertRedirect(route('admin.inclusions'));
    
        $this->assertDatabaseHas('property_features', [
            'id' => $inclusion->id,
            'name' => $updatedData['name'],
        ]);
    }

    //Soft Delete
    public function test_inclusion_can_be_soft_deleted()
    {
        $inclusion = PropertyFeature::factory()->create();

        Livewire::test(ViewInclusion::class, ['inclusion' => $inclusion])
            ->set('confirmItemDelete',  $inclusion->id) 
            ->call('deleteInclusion')
            ->assertHasNoErrors();

        $this->assertSoftDeleted('property_features', ['id' => $inclusion->id]);
    }

    //Soft Delete In List
    public function test_inclusions_can_be_soft_deleted_in_list()
    {
        $inclusion = PropertyFeature::factory()->create();

        $user = User::factory()->create();
        $user->givePermissionTo('event-inclusions-soft-delete');
    
        Livewire::actingAs($user)
            ->test(ViewInclusions::class) 
            ->set('confirmItemDelete', $inclusion->id)
            ->call('deleteInclusion', $inclusion->id) 
            ->assertHasNoErrors();

        $this->assertSoftDeleted('property_features', ['id' => $inclusion->id]);
    }

    //Permanently Deleted
    public function test_inclusion_can_be_permanently_deleted()
    {
        //creating a record then soft deleting
        $inclusion = PropertyFeature::factory()->create();
        $inclusion->delete();
 
     //set id
     Livewire::test(DeletedInclusions::class)
         ->set('confirmItemDelete', $inclusion->id) //make modal true
         ->call('deleteInclusionForever', $inclusion->id) //passes the id
         ->assertHasNoErrors();
 
     // Assert the payment record was permanently deleted
        $this->assertDatabaseMissing('property_features', [
            'id' => $inclusion->id,
        ]);
    }

    //Bulk Deletes
    public function test_bulk_delete_inclusions_successfully()
    {
    // Create amenity
    $inclusions = PropertyFeature::factory()
        ->count(3)
        ->create([
            'property_type_id' => 3, //create an inclusion
        ]);

    $inclusionIds = $inclusions->pluck('id')->toArray();

    // Confirm all exist before deletion
    foreach ($inclusionIds as $id) {
        $this->assertDatabaseHas('property_features', [
            'id' => $id,
            'deleted_at' => null,
        ]);
    }

    // Attempt bulk delete using View component
    Livewire::test(ViewInclusions::class)
        ->set('selectedRows', $inclusionIds)
        ->set('confirmBulkDelete', true)
        ->call('deleteSelectedRows')
        ->assertSet('confirmBulkDelete', false)
        ->assertHasNoErrors();


    // Confirm all are soft-deleted
    foreach ($inclusionIds as $id) {
        $this->assertSoftDeleted('property_features', [
            'id' => $id,
        ]);
    }
    }
}
