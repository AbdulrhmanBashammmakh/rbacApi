<?php

namespace App\Actions\Fortify;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();
      $user=  User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'username' => $input['email'],
            'password' => Hash::make($input['password']),
            'role_id'=>4,
            'remember_token' => Str::random(20),

        ]);
      //  $permission_new_user = Permission::where('table_name','=','users')->pluck('id')->toArray();

      //  $user_role = Role::where('key','User')->first();
      //  $user_role->permission()->sync( $permission_new_user);

        return $user;

    }
}
