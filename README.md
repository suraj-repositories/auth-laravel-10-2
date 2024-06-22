
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# auth-laravel10-2

Role + authority based login system. User can access only those functionalities which is assigned to him. Nothing else

### Ways to Achieve
There are different way's to achieve this most common are : 

- using providers 
- using Seeders
- using policies

    In this example we are going to use Providers

### Steps

- Require laravel breeze which provides prebuilt authentication
```sh
composer require laravel/breeze --dev
```
- install the breeze to you applicaton
```sh
php artisan breeze:install
```
- require the spatie which provides role and permission based login machanism
```sh
composer require spatie/laravel-permission
```
- Publish the migration and configuration files:
```sh
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```
- Run the migrations to create the necessary tables:
```php
php artisan migrate
```

- make seeder to create necessary roles and permissions at the begining -=- you can also add other whenever you need 
```sh
php artisan make:seeder RolesAndPermissionsSeeder
```
 write the seeder code then 
run the seeder

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define roles
        $adminRole = Role::create(['name' => 'admin']);
        $editorRole = Role::create(['name' => 'editor']);

        // Define permissions
        $permissions = [
            'create_posts',
            'edit_posts',
            'delete_posts',
            'publish_posts',
            'manage_users',
        ];

        // Assign permissions to roles
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $adminRole->syncPermissions($permissions);
        $editorRole->syncPermissions(['create_posts', 'edit_posts']);
    }
}

```

```sh
php artisan db:seed --class=RolesAndPermissionsSeeder
```

after this make use of useRoles and useAuthorities on your User model
```php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;

class User extends Authenticatable
{
    use HasRoles, HasPermissions;
    # rest of the code...
}

```
<br>
You don't externally need to create migration for role and  permissions table 'laravel spatie' will manage this on his side

- now you can assign role to the user like this after creating user
```php
 $user->assignRole('admin');
```
- also you can assign permissions 
```php
$user->assignRole(['writer', 'admin']);
```

- How to get roles and permission of currently logged in user -- here is the way
```php
auth()->user()->hasRole('admin')
```
```php
auth()->user()->can('edit articles')    #permissions
```

```php
Auth::user()->hasRole('admin')
```
```php
Auth::user()->can('edit articles')   #permission
```
```php
$user->hasRole('admin')
```
```php
$user->can('edit articles')    #permissions
```

- get all permissions or roles
```php 
$user->getAllPermissions();
```
```php 
auth()->user()->getAllPermissions();
```
```php
$user->roles;
```
```php
auth()->user()->roles;
```
```php
Auth::user()->roles;
```


## Create Middleware to test the role then permit the URL

- step 1

```php
php artisan make:middleware RoleMiddleware
```
- code inside role middleware 

```php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        if(!Auth::check()){
            abort(403, 'Unauthorized');
        }else if (!auth()->user()->hasRole($roles)) {   
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
```
- step 3 : Register your middleware in Kernel.php
```php
  protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'role' => \App\Http\Middleware\RoleMiddleware::class,
  ];
```

- step 4 : here is how to use your middleware 
```php
Route::get('/admin/dashboard', function () {
    return "This is ADMIN DASHBOARD DEMO";
})->middleware('role:admin');
```

- now /admin/dashboard route is only accessable by admin role only
- In the same way you can also create permission middleware too..


<br><br>
<div align="center">**************** THANKS ****************</div>
