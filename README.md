# Tic Tac Toe Lumen API

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

Make sure your server meets the following requirements:

```
PHP >= 5.6.4
OpenSSL PHP Extension
PDO PHP Extension
Mbstring PHP Extension
```

### Installing

First, clone the repo:
```
$ git clone git@github.com:TolgaYigit/tictactoe-lumen-api.git
```
#### Install dependencies
```
$ cd tictactoe-lumen-api
$ composer install
```
#### Configure the Environment
Create `.env` file:
```
$ cat .env.example > .env
```
If you want you can edit database name, database username and database password.


#### Migrations
First, we need connect to the database. For homestead user, login using default homestead username and password:

Run the Artisan migrate command:
```
$ php artisan migrate
```
If you want to seed the database with fake data run the following command instead:
```
$ php artisan migrate --seed
```

### API Routes
| HTTP Method	| Path | Action | Desciption  |
| ----- | ----- | ----- | ------------- |
| POST     	| /login | authenticate| Login as user
| POST     	| /register | register | Create an user
| GET      	| /user/{user_id}* | getSingleUser |  Fetch an user by id
| GET      	| /users* | getUserList |  Fetch all users
| PUT      	| /users/{user_id}* | updateUser | Update an user by id
| DELETE   	| /users/{user_id}* | deleteUser | Delete an user by id
| GET		| /games/available* | listAvailableGames | Lists available games
| GET		| /game/{game_id}* | getGameInfo | Fetch a game by id
| POST 		| /game/join* | joinBattle | Join a battle or create one if none available
| POST 		| /game/placemarker* | placeMarker | Place marker

*Route needs authentication

### Authentication

#### Creating api_key and Login
The api_key is used for granting access to a user. For creating api_key we have to use the `/login` route. Here is an example of creating api_key with [Postman](https://www.getpostman.com/).
![api_key_creation](/public/docs/images/register.png?raw=true "api_key creation example")

#### Using the api_key
Some routes are restricted to public use. To use those routes you have to login and send request with the api_key you have should put it in the HTTP "Authorization" header.
![authorization](/public/docs/images/authorization.png?raw=true "authorization example")

### Playing the Game
Tic-tac-toe is a paper-and-pencil game for two players, X and O, who take turns marking the spaces in a (usually) 3×3 grid. The player who succeeds in placing three of their marks in a horizontal, vertical, or diagonal row wins the game.

![board](/public/docs/images/board.png?raw=true "board example")

#### Join Battle
By using the `/game/join` route you can join any available game. If there isn't any game available to join, API creates one automatically for you. 
The `/game/join` route returns `Game` object as response.
```
{
    "data": {
        "id": 2,
        "start_time": {
            "date": "2017-08-13 17:10:26.748287",
            "timezone_type": 3,
            "timezone": "UTC"
        },
        "pX": 1,
        "pO": 5,
        "size": 3,
        "result": null,
        "status": 1,
        "created_at": "2017-08-13 13:01:41",
        "updated_at": "2017-08-13 17:10:26"
    },
    "message": "OK"
}
```

| `GAME` status	| Desciption  |
| ----- | ------------- |
| 0		| Finished
| 1    	| Waiting for player
| 2     | Online

#### Place Marker
To place marker to a game use `/game/placemarker`.  If desired the marker location isn't able play (i.e. the location is full) `error` response returns.

```
{
    "data": {
        "id": 3,
        "start_time": null,
        "pX": 5,
        "pO": 1,
        "size": 3,
        "result": null,
        "status": 2,
        "created_at": "2017-08-13 16:54:05",
        "updated_at": "2017-08-13 16:54:05",
        "moves": [
            {
                "user_id": 5,
                "y_axis": "2",
                "x_axis": "2",
                "turn": 1,
                "game_id": 3,
                "updated_at": "2017-08-13 18:04:21",
                "created_at": "2017-08-13 18:04:21",
                "id": 17
            }
        ]
    },
    "message": "Now it's your oppenents turn."
}
```
| `GAME` result| Desciption  |
| ----- | ------------- |
| 0		| Tie
| 1...n | User_Id



## Built With

* [Lumen](https://lumen.laravel.com/) - The micro-framework by Laravel

## License

The Lumen framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)