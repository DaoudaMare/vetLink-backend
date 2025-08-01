<?php
namespace App\Repositories;

use App\Models\User;
use App\Models\ProfileProgress;
use App\Interfaces\ProfileProgressInterface;

class ProfileProgressRepository implements ProfileProgressInterface
{
    public function getProfileProgress()
    {
        return ProfileProgress::where('user_id', $userId)->with('user')->first();
    }

    public function calculateAndUpdateProgress(User $user)
    {
        $progress = ProfileProgress::firstOrNew(['user_id' => $user->id]);

        $steps = $this->getStepsForUser($user);
        $totalSteps = count($steps);
        $completedStepsCount = 0;

        foreach ($steps as $field) {
            if (!empty($user->{$field})) {
                $completedStepsCount++;
            }
        }

        // Logique pour les documents
        $documentCount = $user->documents()->count();
        if ($user->userType && $user->userType->title === 'Producteur') {
            $totalSteps += 2; // 2 documents requis pour les producteurs
            if ($documentCount >= 2) {
                $completedStepsCount += 2;
            }
        } else {
            $totalSteps += 1; // 1 document requis pour les autres
            if ($documentCount >= 1) {
                $completedStepsCount += 1;
            }
        }

        $completionPercentage = ($totalSteps > 0) ? ($completedStepsCount / $totalSteps) * 100 : 0;

        $progress->completed_steps = $completedStepsCount;
        $progress->total_steps = $totalSteps;
        $progress->completion_percentage = $completionPercentage;
        $progress->save();

        return $progress;
    }

    public function getStepsForUser(User $user): array
    {
        $commonSteps = ['firstName', 'lastName', 'email', 'tel1', 'address'];

        if ($user->userType && $user->userType->title === 'Producteur') {
            return array_merge($commonSteps, ['organization_id']);
        }

        return $commonSteps;
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
      $profileProgress = ProfileProgress::where('user_id', $id)->with('user')->first();
      return $profileProgress;
    }
}