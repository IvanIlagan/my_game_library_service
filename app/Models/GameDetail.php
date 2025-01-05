<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class GameDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'platforms',
        'image_url',
        'gb_game_id'
    ];

    protected function casts(): array
    {
        return [
            'platforms' => 'array'
        ];
    }
}