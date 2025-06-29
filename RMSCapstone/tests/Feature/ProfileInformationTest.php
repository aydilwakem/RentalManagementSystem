<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileInformationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_current_profile_information_is_available(): void
    {
        $this->actingAs($user = User::factory()->create());

        $component = Livewire::test(UpdateProfileInformationForm::class);

        $this->assertEquals($user->name, $component->state['name']);
        $this->assertEquals($user->email, $component->state['email']);
    }

    public function test_profile_information_can_be_updated(): void
    {
       
        $user = User::factory()->create([
            'name' => 'Original Name',
            'last_name' => 'Original',
            'middle_name' => 'Orginal',
            'suffix' => 'Orginal',
            'email' => 'original@example.com',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        Livewire::test(UpdateProfileInformationForm::class)
            ->set('state.name', 'Test Name') 
            ->set('state.midlle_name', 'Test Name') 
            ->set('state.last_name', 'Test Name') 
            ->set('state.suffic', 'Test Name') 
            ->set('state.email', 'test@example.com')
            ->call('updateProfileInformation')
            ->assertHasNoErrors();

        $user->refresh();

        $this->assertEquals('Test Name', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at); // email change resets verification
    }
}
