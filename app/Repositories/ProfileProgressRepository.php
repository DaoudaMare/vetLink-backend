<?php
namespace App\Repositories;

use App\Models\User;
use App\Models\ProfileProgress;
use App\Interfaces\ProfileProgressInterface;

class ProfileProgressRepository implements ProfileProgressInterface

{
    public function getProfileProgress()
    {

    }
    public function createProfileProgress(array $data)
    {
      return ProfileProgress::create($data);
    }
    public function updateProfileProgress(ProfileProgress $profile_user,array $data)
    {
      $profile_user->update($data);
        return $profile_user;
    }
    public function deleteProfileProgress()
    {

    }
    public function showUserProfile(string $id)
    {
      $profileProgress = ProfileProgress::where('user_id', $id)->first();
      return $profileProgress;
    }
  
}