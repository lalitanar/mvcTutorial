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

### Backend API (VSCode)
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
   - `php artisan make:controller GdstudentsController --api --model=gdstudents`
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
