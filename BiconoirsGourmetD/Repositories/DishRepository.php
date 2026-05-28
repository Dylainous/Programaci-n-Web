<?php
namespace App\Repositories;

class DishRepository {
    private string $file;
    private array $defaults = [
        ['id' => 1, 'name' => 'Hamburguesa Biconoir', 'description' => 'Carne premium y queso cheddar.', 'price' => 12.50, 'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=500', 'ingredients' => []],
        ['id' => 2, 'name' => 'Ensalada Green',       'description' => 'Mix de lechugas y aguacate.',   'price' =>  8.90, 'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=500', 'ingredients' => []],
    ];

    public function __construct() {
        $this->file = DB_PATH . 'dishes.json';
    }

    public function findAll(): array {
        return $this->read();
    }

    public function save(array $data): void {
        $all = $this->read();
        $data['id'] = $data['id'] ?? time();
        $all[] = $data;
        $this->write($all);
    }

    public function update(int|string $id, array $changes): bool {
        $all     = $this->read();
        $updated = false;
        foreach ($all as &$row) {
            if ((string)$row['id'] === (string)$id) {
                $row     = array_merge($row, $changes);
                $updated = true;
                break;
            }
        }
        if ($updated) $this->write($all);
        return $updated;
    }

    private function read(): array {
        if (!file_exists($this->file)) { $this->write($this->defaults); return $this->defaults; }
        return json_decode(file_get_contents($this->file), true) ?? [];
    }

    private function write(array $data): void {
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
