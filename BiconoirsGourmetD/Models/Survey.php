<?php
namespace App\Models;

class Survey {
    public function __construct(
        public readonly int    $id,
        public readonly string $customer,
        public readonly int    $rating,
        public readonly string $comment,
    ) {}

    public function toArray(): array {
        return [
            'id'       => $this->id,
            'customer' => $this->customer,
            'rating'   => $this->rating,
            'comment'  => $this->comment,
        ];
    }
}
