<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'cost', 'image'
    ];
    protected $table = 'products';

    public static function mathces($query_str)
    {
        return self::when($query_str, function ($query, $query_str) {
            return $query->where('name', 'LIKE', "%{$query_str}%");
        });
    }
}
