<?php
namespace App\Models;

class Dish {
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly string $description,
        public readonly float  $price,
        public readonly string $image,
        public readonly array  $ingredients = [],
    ) {}

    public function toArray(): array {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => $this->price,
            'image'       => $this->image,
            'ingredients' => $this->ingredients,
        ];
    }
}
