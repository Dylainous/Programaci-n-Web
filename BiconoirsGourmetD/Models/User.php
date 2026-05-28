<?php
namespace App\Models;

class User {
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $role = 'customer',
    ) {}

    public function isAdmin(): bool {
        return $this->role === 'admin';
    }

    public function toArray(): array {
        return [
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => $this->password,
            'role'     => $this->role,
        ];
    }
}
