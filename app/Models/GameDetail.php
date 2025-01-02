<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class GameDetail extends Model
{
    protected $fillable = [
        'name',
        'description',
        'platforms',
        'image_url'
    ];

    protected function casts(): array
    {
        return [
            'platforms' => 'array'
        ];
    }
}