## Introduction
Laravel Plugin Library for Creating a new Eloquent model class filled with getter and setter.

## Quick Start
1.Import package via composer  

```
composer require tinson/laravel-make-model:^1.0.0
```  

2.Import provider at `bootstrap/app.php` in your project  

```
$app->register(MakeModel\Providers\MakeModelProvider::class);
```

3.How to use
```
# see the help list  
php artisan make:e-model -h

# a simple demo 
# create new model named `User`, its table name is `users`.  
php artisan make:e-model users -m User
```