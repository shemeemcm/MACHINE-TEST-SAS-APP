<?php

namespace App\Interfaces;

interface AuthRepositoryInterface
{
    /**
     * Create a new user record in the database.
     */
    public function createUser(array $data);

    /**
     * Find a user by their email address.
     */
    public function findUserByEmail(string $email);

    /**
     * Update a user's password.
     */
    public function updatePassword($user, string $hashedPassword);
}
