# mvcTutorial

## Backend API: Laravel 9 API

### Laravel 9 Installation
Doc: https://laravel.com/docs/9.x/installation#installation-via-composer

1. Install MAMP (MAC) or XAMP (Windows): 
   - Install MAMP & XAMP
     - Reqirement: PHP8 
     - MAMP: https://www.mamp.info/en/downloads/
     - XAMP: https://www.apachefriends.org/download.html
   - Install manually (MAC)
     - Intall Home-brew
       - Mac: 
         - `/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"`
     - Install PHP 8
       - Mac: 
         - `brew install php`
         - Test: `php --version`
2. Install composer
   - Mac:
     - Doc: https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos
     - `curl -sS https://getcomposer.org/installer | php`
     - `php composer.phar`
     - `sudo mv composer.phar /usr/local/bin/composer`
     - `Test: composer`
   - Windows:
     - Doc: https://getcomposer.org/doc/00-intro.md#installation-windows 
3. Install Laravel
   - `composer global require laravel/installer`
   - `nano  ~/.bash_profile`
   - Add following line in .bash_profile
     - `export PATH="$PATH:$HOME/.composer/vendor/bin"`
     - `source ~/.bash_profile  `
5. Create Laravel project
   - `laravel new eabackend`
   - `cd eabackend`
   - `php artisan serve`

## Backend API (VSCode)
### Project configuration and preparation for APIs
1. Start MySQL Server
2. MySQL DB configuration
   ```php
   //.env file
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=eabackend
   DB_USERNAME=root
   DB_PASSWORD=root
   ```
3. Create eastudents migration file
   - `php artisan make:model eastudents -m`
   - _Note: -m is to create migration file
4. Update migration file in database/migrations
   ```php
      Schema::create('eastudents', function (Blueprint $table) {
          $table->id();
          $table->string('firstName');
          $table->string('lastName')->nullable();
          $table->string('major');
          $table->tinyInteger('status')->default(1);
          $table->timestamps();
      });

   ```
5. Update eastudetns.php in app/Models 
   ```php
   class eastudents extends Model
   {
      use HasFactory;
      protected $guarded = [];  //Add this line
   }
   ```
   - $fillable = [field1, field2, ...] คือกำหนดชื่อของ column ที่อนุญาตให้เข้าถึง
   - $guarded = [field1, field2, ...] คือกำหนดชื่อของ column ที่ไม่อนุญาตให้เข้าถึง ถ้าเขียนเป็น array ว่าง **[]** ก็หมายถึงอนุญาติทั้งหมดครับ

6. Migrate schema to MySQL database
   - `php artisan migrate`
7. Create Resource file: EastudentsResource.php in app/Http/Reourses
   - `php artisan make:resource EastudentsResource`
8. Update EastudentsResource.php in app/Http/Resources
   ```php
   public function toArray($request)
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'major' => $this->major,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
   ```
9. Create Controller file in app/Http/Controllers
   - `php artisan make:controller EastudentsController --api --model=eastudents`
10. Check current routes' list
   - `php artisan route:list`
   ```
   //Show in Terminal
   GET|HEAD   / ............................................................... 
   POST       _ignition/execute-solution ignition.executeSolution › Spatie\Lar…
   GET|HEAD   _ignition/health-check ignition.healthCheck › Spatie\LaravelIgni…
   POST       _ignition/update-config ignition.updateConfig › Spatie\LaravelIg…
   GET|HEAD   api/user ........................................................ 
   GET|HEAD   sanctum/csrf-cookie . Laravel\Sanctum › CsrfCookieController@show
   ```
   ```php
   //There is a user path due to the code in api.php

   Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
      return $request->user();
   });

   ```
11. Update routes/api.php   
    ```php
    use App\Http\Controllers\EastudentsController; // Add this line
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    
    Route::resource('eastudents', EastudentsController::class); //Add this line

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
       return $request->user();
    });
    ```
    - Check route list again 
      - `php artisan route:list`
      - eastudents APIs will be created
      
### Create eastudents APIs: Create/Read/Update/Delete eastudents
1. Prepare library
   ```php
   namespace App\Http\Controllers;

   use App\Http\Resources\EastudentsResource; //Add this line
   use App\Models\eastudents; //Add this line
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Validator; //Add this line
   use Illuminate\Support\Facades\Log; //Add this line
   ```
2. Show all eastudents
   ```php
   public function index()
    {
        // Fetch Data
        $eastud = eastudents:: latest()->get();

        // Return Message and Data
        return response()->json(['EAStudents fetch sucessfully', EastudentsResource::collection($eastud)]);
    }
   ```
   - Postman: GET METHOD: http://127.0.0.1:8000/api/eastudents
   - GET|HEAD api/eastudents
   
3. Create a eastudent
   ```php
   public function store(Request $request)
    {
        //check validator
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:50',
            'lastName' => 'required|string|max:50',
            'major' => 'required|string|max:10',
        ]);
        
        //If validator fail
        if($validator->fails()){
            //Return validator error message
            return response()->json($validator->errors());
        }
        
        //Created data
        $eastd = eastudents::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'major' => $request->major
        ]);

        //Return message and data
        return response()->json(['eastudetns created sucessfully', new EastudentsResource($eastd)]);
             
    }
   ```
   - Postman: POST METHOD: http://127.0.0.1:8000/api/eastudents
     - Body
       ```json
       {
         "firstName" : "Bob",
         "lastName" : "Cat",
         "major" : "EGCO"
       }
       ```
   - POST api/eastudents
4. Show a eastudent
   ```php
   public function show(eastudents $eastudent)
    {
        //Return data
        //Log::channel('stderr')->info($eastudent);
        return response()->json($eastudent);
    }
   ```
   - Postman: GET METHOD : http://127.0.0.1:8000/api/eastudents/3
   - GET|HEAD api/eastudents/{eastudent}  
   - 
5. Update a eastudent
   ```php
   public function update(Request $request, eastudents $eastudent)
    {
        //check validator
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:50',
            'lastName' => 'string|max:50|nullable',
            'major' => 'required|string|max:10',
            'status' => 'numeric|nullable'
        ]);
        //If validator fail
        if($validator->fails()){
            //Return validator error message
            return response()->json($validator->errors());
        }
        //Set data
        $eastudent->firstName = $request->firstName;
        $eastudent->lastName = $request->lastName;
        $eastudent->major = $request->major;
        $eastudent->status = $request->status;

        //Update data
        $eastudent->save();

        //Return message and data
        return response()->json(['eastudents updated sucessfully', new EastudentsResource($eastudent)]);
        
    }

   ```
   - Postman: PUT METHOD: http://127.0.0.1:8000/api/eastudents/1
     - Body
     ```json
      {
         "firstName" : "Bob",
         "lastName" : "Cat",
         "major" : "EGEE",
         "status" : 0
      }
     ```
   - PUT|PATCH api/eastudents/{eastudent}
   
6. Delete a eastudent
   ```php
   public function destroy(eastudents $eastudent)
    {
        //Delete data
        $eastudent->delete();
        //return message
        return response()->json(['eastudents deleted sucessfully']);
    }
   ```
   - Postman: DELETE METHOD: http://127.0.0.1:8000/api/eastudents/3
   - DELETE api/eastudents/{eastudent}


## Backend API Authentication (Authentication Sanctum)

### Installation
**Option**
- Install sanctum library `composer require laravel/sanctum`
- Check composer.json
  ```json
  "require": {
        "php": "^8.0.2",
        "guzzlehttp/guzzle": "^7.2",
        "laravel/framework": "^9.11",
        "laravel/sanctum": "^2.14.1", //Sanctum
        "laravel/tinker": "^2.7"
    },
    ```

- Terminal: `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`
  -  Create 2 files for Token

### Set up Sanctum
1. Update Kernel.pnp in app/Http
   - Update api section tfollowing the codes below
   ```php
   'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
   ```
   
2. Create AuthApiController.php in app/Http/Controllers
   - `php artisan make:controller AuthApiController`

3. Include library in AuthApiController.php
   ```php
   namespace App\Http\Controllers;

   use App\Models\User;
   use Illuminate\Http\Request;
   use Auth;    // OR  use Illuminate\Support\Facades\Auth;
   ```
  
4. Create response data
   ```php
   public function response($user){
        $token = $user->createToken(str()->random(40))->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }
   ```
   
5. Create register API
   ```php
   public function register(Request $request){
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4|confirmed'
        ]);

        $user = User::create([
            'name' => ucwords($request->name),
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return $this->response($user);
    }
    ```
    - Postman environment setting: ENV_LARAVEL_API
      ```json
      {
	      "id": "1410a100-eaf8-467e-bec3-897a79750358",
	      "name": "ENV_LARAVEL_API",
	      "values": [
		      {
			      "key": "URL",
			      "value": "http://127.0.0.1:8000/api",
			      "type": "default",
			      "enabled": true
		      },
		      {
		      	"key": "TOKEN",
		      	"value": "",
		      	"type": "default",
		      	"enabled": true
		      }
	      ],
	      "_postman_variable_scope": "environment",
	      "_postman_exported_at": "2022-05-22T07:30:42.407Z",
      	"_postman_exported_using": "Postman/9.19.3"
      }
      ```
    - Postman Headers Presets
      ```json
      {
         "KEY": "X-Requested-With",
         "VALUE": "XMLHttpRequest"
      }
      ```
    - Postman Test script
        ```php
      	   if(pm.response.to.have.status(200)){
    	      var jsonData = JSON.parse(responseBody);
    	      pm.environment.set("TOKEN", `${jsonData.token_type} ${jsonData.token}`);
	   }
        ```
    - Postman: POST METHOD: {{URL}}/register
    - POST api/register
      - Body
        ```json
	    {
               "name" : "John",
               "email" : "John@mail.com",
               "password" : "1234",
               "password_confirmation" : "1234"
            }
 	```
    
6. Create login API
   ```php
   public function login(Request $request) {
        $cred = $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|min:4'
        ]);

        if(!Auth::attempt($cred)) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 401);
        }

        //Auth::user() -> Retrieve the currently authenticated user...
        return $this->response(Auth::user());  
    }
   ```
   - Add Test script
   - Postman: {{URL}}/login
   - POST api/login
     - Body
     ```json
     {
        "email" : "John@mail.com",
        "password" : "1234"
     }
     ```
   
7. Create logout API
   ```php
   public function logout(){
        Auth::user()->tokens()->delete();

        return response()->json([
            'message' => 'You have successfully logged out and token was successfully deleted.'
        ]);
    }
   ```
   - Add Test script
   - Postman: {{URL}}/logout
   - POST api/logout

8. Set the authentication for assign API
   - Update routes/api.php
     - Include library
       ```php
       use App\Http\Controllers\EastudentsController;
       use App\Http\Controllers\AuthApiController;
       use Illuminate\Http\Request;
       use Illuminate\Support\Facades\Route;
       ```
     - Set routes for register and login API without authentication 
       ```php
       Route::post('register', [AuthApiController::class, 'register']);
       Route::post('login', [AuthApiController::class, 'login']);
       ```
     - Set route for user API and eastudent APIs (CRUD) with authentication
       ```php
       Route::middleware('auth:sanctum')->group(function(){
         Route::post('logout', [AuthApiController::class, 'logout']);
            Route::get('user', function(Request $request){
         return $request->user();
         });
         //eastudents APIs Require Token Authentication
         Route::resource('eastudents', EastudentsController::class);
       });
       ```
     - If APIs do not require the authentication, move the Route to be outside the middleware 



