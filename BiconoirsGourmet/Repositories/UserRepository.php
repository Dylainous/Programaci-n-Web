<?php
namespace App\Repositories;

class UserRepository {
    private string $file;
    private array $defaults = [
        ['name' => 'Admin',           'email' => 'admin@biconoir.com',   'password' => 'admin123',    'role' => 'admin'],
        ['name' => 'Cliente Ejemplo', 'email' => 'customer@example.com', 'password' => 'customer123', 'role' => 'customer'],
    ];

    public function __construct() {
        $this->file = DB_PATH . 'users.json';
    }

    public function findByCredentials(string $email, string $password): ?array {
        foreach ($this->read() as $row) {
            if ($row['email'] === $email && $row['password'] === $password) return $row;
        }
        return null;
    }

    public function save(array $data): void {
        $all   = $this->read();
        $all[] = $data;
        $this->write($all);
    }

    private function read(): array {
        if (!file_exists($this->file)) { $this->write($this->defaults); return $this->defaults; }
        return json_decode(file_get_contents($this->file), true) ?? [];
    }

    private function write(array $data): void {
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
