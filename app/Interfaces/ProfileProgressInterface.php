<?php
namespace App\Interfaces;

use App\Models\ProfileProgress;

interface ProfileProgressInterface
{
    public function getProfileProgress();
    public function createProfileProgress(array $data);
    public function updateProfileProgress(ProfileProgress $profile_user ,array $data);
    public function deleteProfileProgress();
    public function showUserProfile(string $id);
}