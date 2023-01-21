## Laravel 9 REST API

Thanks to <a href="https://github.com/urnauzao" alt="github-profile">urnauzao</a>. Here the original 
<a href="https://github.com/urnauzao/api-example" alt="repo">repo</a>

### Download a Laravel Sail example app 
curl -s "https://laravel.build/example-app?with=mysql,redis" | bash

### Generate controller, model, apiResources, controller test and form requests
php artisan make:controller CourseController --model=Course --api --test --requests

### Create migration
php artisan make:migration create_courses_table

### Run the migration in enviroment testing. Don't forget to create the env.testing file 
sail artisan --env=testing migrate      
#### * (1)

### Run the migration in environment default
sail artisan migrate


*(1) By default, Sail commands are invoked using the vendor/bin/sail script that is included with all new Laravel applications:

./vendor/bin/sail up

However, instead of repeatedly typing vendor/bin/sail to execute Sail commands, you may wish to configure a shell alias that allows you to execute Sail's commands more easily:

alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'

To make sure this is always available, you may add this to your shell configuration file in your home directory, such as ~/.zshrc or ~/.bashrc, and then restart your shell.

(<a href="https://laravel.com/docs/9.x/sail" ref="laravel-doc">Laravel Sail Documentation</a>)


