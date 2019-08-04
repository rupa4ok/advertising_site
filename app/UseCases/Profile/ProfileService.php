<?php

namespace App\UseCases\Profile;

use App\Models\User;
use App\Http\Requests\Auth\ProfileEditRequest;

class ProfileService
{
    public function edit($id, ProfileEditRequest $request): void
    {
        /** @var User $user */
        $user = User::findOrFail($id);
        $oldPhone = $user->phone;
        $user->update($request->only('name', 'last_name', 'phone'));
        if ($user->phone !== $oldPhone) {
            $user->unverifyPhone();
        }
    }
    
    public function delete($id, ProfileEditRequest $request): void
    {
        /** @var User $user */
        $user = User::findOrFail($id);
        $user->delete();
    }
    
    private function getUser($userId): User
    {
        return User::findOrFail($userId);
    }
}
