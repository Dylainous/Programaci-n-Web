<?php
namespace App\Models;

class Reservation {
    public function __construct(
        public readonly int    $id,
        public readonly string $customer,
        public readonly string $date,
        public readonly string $type,
    ) {}

    public function toArray(): array {
        return [
            'id'       => $this->id,
            'customer' => $this->customer,
            'date'     => $this->date,
            'type'     => $this->type,
        ];
    }
}
