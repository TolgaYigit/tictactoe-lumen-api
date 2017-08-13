<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Move extends Model
 
{
   protected $table = 'moves';

   protected $guarded = [];
 
   public function game()
   {
   		$this->belongsTo('App\Models\Game');
   }
 
}