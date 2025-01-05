<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class PlayerOwnedGame extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'game_id',
        'rating',
        'review',
        'times_played',
        'is_finished'
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'date'
        ];
    }
}