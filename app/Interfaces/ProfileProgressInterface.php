<?php
namespace App\Interfaces;

interface ProfileProgressInterface
{
    public function getProfileProgress();
    public function createProfileProgress(array $data);
    public function updateProfileProgress(array $data);
    public function deleteProfileProgress();
}