<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;

use Auth;
 
class Game extends Model
 
{ 
   protected $table = 'games';
   protected $guarded = [];
 
   	public function scopeAvailable($query)
    {
    	if(Auth::id() > 0)
    	{
	        return $query->where(function($q) {
	        	return $q->where('pX', null)
	        			->orWhere('pO', null);
	        })->where(function($q) {
	        	return $q->where('pX', '!=', Auth::id())
	        		->orWhere('pO', '!=', Auth::id());
	        })
	        ->where('status', 1);
	    }
    }

    public function scopeIsPlayer($query, $user_id)
    {
    	return $query->where('pX', $user_id)->orWhere('pO', $user_id);
    }

    public function scopeActive($query)
    {
    	return $query->where('status', 2);
    }

    /*public function scopeCanPlace($query, $x, $y)
    {
    	return $query->whereHas('moves', function($q) use($x, $y){
    		$q->where([['x_axis', '!=', $x],['y_axis', '!=', $y]]);
    	});
    }*/

    public function getEmptyRoom(){
    	if($this->pO == null){
    		return 'pO';
    	} else {
    		return 'pX';
    	}
    }
   	
	/*public function players()
	{
   		return [$this->playerX, $this->playerO];
   	}*/

   	public function moves()
   	{
   		return $this->hasMany('App\Models\Move');
   	}

   	public function playerX()
   	{
   		return $this->belongsTo('App\Models\User', 'pX');
   	}

   	public function playerO()
   	{
   		return $this->belongsTo('App\Models\User', 'pO');
   	}
}