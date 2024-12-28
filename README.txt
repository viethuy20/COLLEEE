Install
Clone the git repository on your computer $ git clone https://github.com/viethuy20/COLLEEE.git

Cd to project
$ cd sns_organic

Copy the .env.example file to .env

$ cp .env.example .env

Run composer
$ composer install $ composer dump-autoload

Setup
Generate the application key
$ php artisan key:generate
