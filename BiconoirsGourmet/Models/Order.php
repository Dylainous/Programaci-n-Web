<?php
namespace App\Models;

class Order {
    public function __construct(
        public readonly string $id,
        public readonly string $customerName,
        public readonly string $customerEmail,
        public readonly array  $items,
        public readonly float  $total,
        public readonly string $status    = 'En Preparación',
        public readonly string $createdAt = '',
    ) {}

    public function toArray(): array {
        return [
            'id'             => $this->id,
            'customer_name'  => $this->customerName,
            'customer_email' => $this->customerEmail,
            'items'          => $this->items,
            'total'          => $this->total,
            'status'         => $this->status,
            'created_at'     => $this->createdAt,
        ];
    }
}
