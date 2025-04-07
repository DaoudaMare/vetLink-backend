<?php
namespace App\Interfaces;

use App\Models\User;

interface UserInterface
{
    public function getAll();
    public function register(array $data);
    public function inLogin(array $credentials);
    public function profile();
    public function update(User $user, array $data);
    public function delete(User $user);
}