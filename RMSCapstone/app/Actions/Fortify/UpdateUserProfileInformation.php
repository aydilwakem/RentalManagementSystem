<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'suffix' => ['nullable', 'string', 'max:10'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'house_number' => ['nullable', 'string', 'max:20'],
            'street' => ['nullable', 'string', 'max:150'],
            'barangay' => ['nullable', 'string', 'max:100'],
            'city_municipality' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'region' => ['nullable', 'string', 'max:50'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'country' => ['nullable', 'string', 'max:100'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'middle_name' => $input['middle_name'],
                'last_name' => $input['last_name'],
                'suffix' => $input['suffix'],
                'email' => $input['email'],
                'contact_number' => $input['contact_number'],
                'house_number' => $input['house_number'] ?? null,
                'street' => $input['street'] ?? null,
                'barangay' => $input['barangay'] ?? null,
                'city_municipality' => $input['city_municipality'],
                'province' => $input['province'],
                'region' => $input['region'] ?? null,
                'postal_code' => $input['postal_code'],
                'country' => $input['country'],
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'middle_name' => $input['middle_name'],
            'last_name' => $input['last_name'],
            'suffix' => $input['suffix'],
            'email' => $input['email'],
            'email_verified_at' => null,
            'contact_number' => $input['contact_number'] ?? $user->contact_number,
            'house_number' => $input['house_number'] ?? $user->house_number,
            'street' => $input['street'] ?? $user->street,
            'barangay' => $input['barangay'] ?? $user->barangay,
            'city_municipality' => $input['city_municipality'] ?? $user->city_municipality,
            'province' => $input['province'] ?? $user->province,
            'region' => $input['region'] ?? $user->region,
            'postal_code' => $input['postal_code'] ?? $user->postal_code,
            'country' => $input['country'] ?? $user->country,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
