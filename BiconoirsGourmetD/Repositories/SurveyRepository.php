<?php
namespace App\Repositories;

class SurveyRepository {
    private string $file;

    public function __construct() {
        $this->file = DB_PATH . 'surveys.json';
    }

    public function findAll(): array {
        return $this->read();
    }

    public function save(array $data): void {
        $all   = $this->read();
        $data['id'] = $data['id'] ?? time();
        $all[] = $data;
        $this->write($all);
    }

    private function read(): array {
        if (!file_exists($this->file)) return [];
        return json_decode(file_get_contents($this->file), true) ?? [];
    }

    private function write(array $data): void {
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
