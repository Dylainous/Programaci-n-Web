<?php
namespace App\Repositories;

class OrderRepository {
    private string $file;

    public function __construct() {
        $this->file = DB_PATH . 'orders.json';
    }

    public function findAll(): array {
        return $this->read();
    }

    public function findByUser(string $email): array {
        return array_values(array_filter($this->read(), fn($o) => $o['customer_email'] === $email));
    }

    public function save(array $data): string {
        $id    = time() . rand(100, 999);
        $data  = array_merge($data, ['id' => $id, 'status' => 'En Preparación', 'created_at' => date('Y-m-d H:i:s')]);
        $all   = $this->read();
        $all[] = $data;
        $this->write($all);
        return (string)$id;
    }

    public function updateStatus(int|string $id, string $status): void {
        $all = $this->read();
        foreach ($all as &$row) {
            if ((string)$row['id'] === (string)$id) { $row['status'] = $status; break; }
        }
        $this->write($all);
    }

    private function read(): array {
        if (!file_exists($this->file)) { $this->write([]); return []; }
        return json_decode(file_get_contents($this->file), true) ?? [];
    }

    private function write(array $data): void {
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
