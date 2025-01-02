<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class PlayerOwnedGame extends Model
{
    protected $fillable = [
        'player_id',
        'game_id'
    ];
}