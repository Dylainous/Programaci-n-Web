<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model {
    protected $table = 'dishes';
    protected $primaryKey = 'dish_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'dish_id', 'name', 'description', 'price', 'category', 'image', 'available'
    ];

    public static function getAllAvailable(): array {
        return self::where('available', true)->get()->toArray();
    }
}
