<?php
namespace App\Models;

class FoodType {
    public string $type;

    public function __construct(array $data) {
        $this->type = $data['type'];
    }
}
