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
3. Create Laravel Project
   - `composer global require laravel/installer`
   - `nano  ~/.bash_profile`
   - Add following line in .bash_profile
     - `export PATH="$PATH:$HOME/.composer/vendor/bin"`
5. 
