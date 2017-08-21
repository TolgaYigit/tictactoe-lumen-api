<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Move;
use App\Common\Response;

use Validator;
use Auth;
use Carbon\Carbon;

class GameController extends Controller
{
    private $seats;
    private $user;

    public function __construct()
    {
        $this->seats = collect(['pX', 'pO']);
        $this->user = Auth::user();
    }

    /**
     * Echoes welcome message.
     *
     * @return string
     */

    public function index()
    {
        $game = Game::with('moves')->findOrFail(1);

        $moves = $game->moves;

        return Response::success($moves);
    }
    
    public function getGameInfo($game_id)
    {
        try {
            $game = Game::findOrFail($game_id);
            return Response::success($game);
        } catch (\Exception $e) {
            return Response::error();
        }
    }

    public function listAvailableGames()
    {
        return Response::success(Game::available()->get());
    }

    /**
     * @param  Request $request [game_id, x-axis, y-axis]
     * @return Response::class
     */
    public function placeMarker(Request $request)
    {
        try {
            $game = Game::with('moves')
                    ->isPlayer($this->user->id)
                    ->findOrFail($request->game_id);
                    
            if($game->status != 2){
                return Response::error("This game is not online.", 400);
            }

            $t = $game->moves->first(function ($move) use($request){
                return ($move->x_axis == $request->x_axis && $move->y_axis == $request->y_axis);
            });

            if((bool)$t){
                return Response::error("You can't play to this location.", 400);
            }

            $lastMove = $game->moves->sortBy('turn')->last();

            if(
                ($game->pX != $this->user->id && !isset($lastMove)) || 
                (isset($lastMove) && $lastMove->user_id == $this->user->id)
            ){
                return Response::error('You have to wait for other players move', 400);
            }

            $this->validate($request, [
                'game_id' => 'required|integer',
                'x_axis' => 'required|integer|max:'.$game->size,
                'y_axis' => 'required|integer|max:'.$game->size
            ]);

            $newMove = new Move([
                    'user_id' => $this->user->id,
                    'y_axis' => $request->y_axis,
                    'x_axis' => $request->x_axis,
                    'turn' => isset($lastMove) ? ($lastMove->turn + 1) : 1
                ]);

            $newMove = $game->moves()->save($newMove);

            $game->moves->push($newMove);

            $result = $this->checkResult($game->moves, $request->x_axis, $request->y_axis, (isset($lastMove) ? $lastMove->turn++ : 0), $game->size);

            switch ($result) {
                case 'win':
                    $game = $this->endBattle($game, $this->user->id);
                    return Response::success($game, 'You win!');
                    break;
                case 'draw':
                    $game = $this->endBattle($game, 0);
                    return Response::success($game, 'Draw!');
                    break;
                default:
                    return Response::success($game, "Now it's your oppenents turn.");
                    break;
            }
        } catch (\Exception $e) {
            return Response::error($e->getMessage(), 400);
        }
    }

    private function checkResult($moves, $x, $y, $lastMove, $gameSize)
    {
        //prepare data for conditions
        foreach ($moves as $move) {
            $keyed[$move->x_axis][$move->y_axis] = $move->user_id;
        };
        
        //check horizontal        
        for($i = 0; $i <= $gameSize - 1; $i++){
            if(!isset($keyed[$x][$i]) || $keyed[$x][$i] != $this->user->id)
                break;
            if($i == 3){
                return 'win';
            }
        }

        //check vertical
        for($i = 0; $i <= $gameSize - 1; $i++){
            if(!isset($keyed[$i][$y]) || $keyed[$i][$y] != $this->user->id)
                break;
            if($i == $gameSize - 1){
                return 'win';
            }
        }

        //check diagonal
        if($x == $y){
            for($i = 0; $i <= $gameSize; $i++){
                if(!isset($keyed[$i][$i]) || $keyed[$i][$i] != $this->user->id)
                    break;
                if($i == $gameSize - 1){
                    return 'win';
                }
            }
        }

        //check anti-diagonal
        if($x + $y = $gameSize - 1){
            for($i = 0; $i <= $gameSize; $i++){
                if(!isset($keyed[$i][($gameSize - 1) - $i]) || $keyed[$i][($gameSize - 1) - $i] != $this->user->id)
                    break;
                if($i == ($gameSize - 1)){
                    return 'win';
                }
            }
        }
        //check draw
        if($lastMove == ($gameSize ** 2 - 1)){
            return 'draw';
        }

        return 'continue';
    }

    private function endBattle($game, $result)
    {
        $game->update([
            'result' => $result,
            'status' => 0
        ]);

        return $game;
    }

    public function joinBattle(Request $request)
    {
        try {
            $game = Game::available()->firstOrFail();
            $emptyRoom = $game->getEmptyRoom();

            $game->update([
                $emptyRoom => $this->user->id,
                'start_time' => Carbon::now(),
                'status' => 2,
            ]);
        } catch (\Exception $e) {
            $game = $this->createBattle($this->user->id);
        }

        return Response::success($game);
    }

    private function createBattle($user_id)
    {
        try {
            $newGame = new Game([
                    $this->seats->random() => $user_id,
                    'status' => 1
                ]);
            
            $newGame->save();
            return $newGame;
        } catch (\Exception $e) {
            return Response::error($e);
        }
    }
}
