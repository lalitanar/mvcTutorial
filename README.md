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
   DB_DATABASE=backend
   DB_USERNAME=root
   DB_PASSWORD=root
   ```
3. Create eastudents migration file
   - `php artisan make:model gdstudents -m`
   - _Note: -m is to create migration file
4. Update migration file in database/migrations
   ```php
      Schema::create('gdstudents', function (Blueprint $table) {
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
   class gdstudents extends Model
   {
      use HasFactory;
      protected $guarded = [];  //Add this line
   }
   ```
