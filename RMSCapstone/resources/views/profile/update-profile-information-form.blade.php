<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" id="photo" class="hidden" wire:model.live="photo" x-ref="photo"
                    x-on:change="
                                                                                                                                                                                                                                                                photoName = $refs.photo.files[0].name;
                                                                                                                                                                                                                                                                const reader = new FileReader();
                                                                                                                                                                                                                                                                reader.onload = (e) => {
                                                                                                                                                                                                                                                                    photoPreview = e.target.result;
                                                                                                                                                                                                                                                                };
                                                                                                                                                                                                                                                                reader.readAsDataURL($refs.photo.files[0]);
                                                                                                                                                                                                                                                        " />

                <x-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}"
                        class="rounded-full size-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                        x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Name') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required
                autocomplete="name" />
            <x-input-error for="name" class="mt-2" />

            <x-label for="middle_name" value="{{ __('Middle Name') }}" />
            <x-input id="middle_name" type="text" class="mt-1 block w-full" wire:model="state.middle_name"
                autocomplete="middle_name" />
            <x-input-error for="middle_name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="last_name" value="{{ __('Last Name') }}" />
            <x-input id="last_name" type="text" class="mt-1 block w-full" wire:model="state.last_name" required
                autocomplete="last_name" />
            <x-input-error for="last_name" class="mt-2" />

            <x-label for="suffix" value="{{ __('Suffix') }}" />
            <x-input id="suffix" type="text" class="mt-1 block w-full" wire:model="state.suffix"
                autocomplete="suffix" />
            <x-input-error for="suffix" class="mt-2" />
        </div>

        <!-- Address -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="house_number" value="{{ __('House Number') }}" />
            <x-input id="house_number" type="text" class="mt-1 block w-full" wire:model="state.house_number"
                autocomplete="house_number" />
            <x-input-error for="house_number" class="mt-2" />

            <x-label for="street" value="{{ __('Street') }}" />
            <x-input id="street" type="text" class="mt-1 block w-full" wire:model="state.street"
                autocomplete="street" />
            <x-input-error for="street" class="mt-2" />

            <x-label for="barangay" value="{{ __('Barangay') }}" />
            <x-input id="barangay" type="text" class="mt-1 block w-full" wire:model="state.barangay"
                autocomplete="barangay" />
            <x-input-error for="barangay" class="mt-2" />

            <x-label for="city_municipality" value="{{ __('City/Municipality') }}" />
            <x-input id="city_municipality" type="text" class="mt-1 block w-full" wire:model="state.city_municipality"
                autocomplete="city_municipality" />
            <x-input-error for="city_municipality" class="mt-2" />

            <x-label for="province" value="{{ __('Province') }}" />
            <x-input id="province" type="text" class="mt-1 block w-full" wire:model="state.province"
                autocomplete="province" />
            <x-input-error for="province" class="mt-2" />

            <x-label for="region" value="{{ __('Region') }}" />
            <x-input id="region" type="text" class="mt-1 block w-full" wire:model="state.region"
                autocomplete="region" />
            <x-input-error for="region" class="mt-2" />

            <x-label for="postal_code" value="{{ __('Postal Code') }}" />
            <x-input id="postal_code" type="text" class="mt-1 block w-full" wire:model="state.postal_code"
                autocomplete="postal_code" />
            <x-input-error for="postal_code" class="mt-2" />

            <x-label for="country" value="{{ __('Country') }}" />
            <x-input id="country" type="text" class="mt-1 block w-full" wire:model="state.country"
                autocomplete="country" />
            <x-input-error for="country" class="mt-2" />
        </div>


        <!-- Contact Information - Email and Contact Number -->

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required
                autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && !$this->user->hasVerifiedEmail())
                <p class="text-sm mt-2">
                    {{ __('Your email address is unverified.') }}

                    <button type="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>