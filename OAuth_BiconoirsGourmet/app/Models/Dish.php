<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model {
    protected $table = 'menu_items';
    protected $primaryKey = 'item_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'item_id', 'name', 'description', 'price', 'category', 'image_url', 'is_available'
    ];

    public static function getAllAvailable(): array {
        return self::where('is_available', true)->get()->toArray();
    }
}
