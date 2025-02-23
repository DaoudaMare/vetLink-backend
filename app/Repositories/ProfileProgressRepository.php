<?php
namespace App\Repositories;

use App\Interfaces\ProfileProgressInterface;
use App\Models\ProfileProgress;

class ProfileProgressRepository implements ProfileProgressInterface

{
    public function getProfileProgress()
    {

    }
    public function createProfileProgress(array $data)
    {
      return ProfileProgress::create($data);
    }
    public function updateProfileProgress(array $data)
    {

    }
    public function deleteProfileProgress()
    {

    }
  
}