# Luissobrinho Builder

**Builder** - A handful of tools for Rapid Laravel Development

[![Latest Version](https://img.shields.io/packagist/v/luissobrinho/builder.svg)](https://packagist.org/packages/luissobrinho/builder)
[![Build Status](https://travis-ci.org/luissobrinho/builder.svg?branch=develop)](https://travis-ci.org/Luissobrinho/Builder)
[![Packagist](https://img.shields.io/packagist/dt/luissobrinho/builder.svg)](https://packagist.org/packages/luissobrinho/builder)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](https://packagist.org/packages/luissobrinho/builder)

This is a set of tools to help speed up development of Laravel apps. You can start an app with various parts prewritten (Users, User Meta, Roles, Teams). And it comes with a powerful FormMaker which can generate form content from tables, and objects. It can generate epic CRUD prototypes rapidly with full testing scripts prepared for you, requiring very little editing. It also provides an elegant Cryptography tool which is URL friendly. Finally it brings along some friends with LaravelCollective as a vendor.

##### Author(s):
* [Luis Eduardo Altino](https://github.com/luissobrinho) ([ads.luis.sobrinho@gmail.com](mailto:ads.luis.sobrinho@gmail.com))

## General Requirements
1. PHP 7.3+
2. OpenSSL
2. JSON

## Compatibility and Support
| Laravel Version | Package Tag | Supported |
|-----------------|-------------|-----------|
| 6.x - 7.x | 0.0.11 | yes |

## Installation

Start a new Laravel project:

```
laravel new {project_name}
```
or
```
composer create-project laravel/laravel {project_name}
```

Then run the following to add the Luissobrinho Builder

```
composer require "luissobrinho/builder"
```

Time to publish those assets! Luissobrinho Builder uses LCrud and LForm which have publishable assets.

```
php artisan vendor:publish
```
or
```
php artisan vendor:publish --provider="Luissobrinho\LCrud\LCrudProvider"
```

You now have Luissobrinho Builder installed. Try out the *Starter Kit*.

### Application Starter Kit

!!! warning "Make sure you followed the getting started instructions!"

Luissobrinho Builder provides an elegant solution for starting an application by building the most basic views, controllers, models and migrations for your application. No need to use the `php artisan make:auth` because now you can easily start your whole application with this single command.

!!! tip "BUT, before we do that let's get a few things set up."

In order to make use of the <u>starter kit</u> you will need to modify some files. Check out the modifications below:

Alter the following to your `app/Providers/RouteServiceProvider.php` in the const `HOME`.

```php
public const HOME = '/dashboard';
```

Add the following to your `app/Http/Kernel.php` in the `$routeMiddleware` array.

```
'admin' => \App\Http\Middleware\Admin::class,
'permissions' => \App\Http\Middleware\Permissions::class,
'roles' => \App\Http\Middleware\Roles::class,
'active' => \App\Http\Middleware\Active::class,
```

If you don't want to worry about email activation then remove this from the route's middleware array:
```
'active'
```

Add the following to 'app/Providers/AuthServiceProvider.php' in the boot method

```php
Gate::define('admin', function ($user) {
    return ($user->roles->first()->name === 'admin');
});

Gate::define('team-member', function ($user, $team) {
    return ($user->teams->find($team->id));
});
```

Add the following to 'app/Providers/EventServiceProvider.php' in the $listen property

```php
 \App\Events\UserRegisteredEmail::class => [
    \App\Listeners\UserRegisteredEmailListener::class,
],
```

### Regarding Email Activation

The Starter kit has an email activation component added to the app to ensure your users have validated their email address.
You can disable it by removing the `active` middleware from the `web` routes. You will also have to disable the Notification but it
won't cause any problems if you remove the email activation.

You will also need to set the location of the email for password reminders. (config/auth.php - at the bottom)

```php
'passwords' => [
    'users' => [
        'provider' => 'users',
        'email' => 'emails.password',
        'table' => 'password_resets',
        'expire' => 60,
        'throttle' => 60,
    ],
],
```

#### Things to note
You may try and start quickly by testing the registration but please make sure your app's <u>email</u> is configured or it will throw an exception.
You can do this in the `.env` file easily by setting it to 'log' temporarily

```php
MAIL_DRIVER=log
```

#### Last Steps

Once you've added in all these parts you will want to run the starter command!

```php
php artisan luissobrinho:starter
```

Then you'll have to refresh the list of all classes that need to be included in the project.

```php
composer dump-autoload
```

Then you'll need to migrate to add in the users, user meta, roles and teams tables. The seeding is run to set the initial roles for your application.

```php
php artisan migrate --seed
```

Once you get the starter kit running you can register and login to your app. You can then you can visit the settings section of the app and set your role to admin to take full control of the app.

### What Starter Publishes

#### Controllers
Luissobrinho Builder updated the basic controllers to handle things like creating a profile when a user is registered, as well as setting default return routes to `dashboard` etc. It also provides contollers for handling profile modifications and pages, team management etc. The admin controller handles the admin of users, modifying a user provided the user has the admin role.

* app/Http/Controllers/
    * Admin/
        * DashboardController.php
        * UserController.php
        * RoleController.php
    * Auth/
        * ActivateController.php
        * ForgotPasswordController.php
        * LoginController.php
        * RegisterController.php
        * ResetPasswordController.php
    * User/
        * PasswordController.php
        * SettingsController.php
    * PagesController.php
    * TeamController.php

#### Middleware
Luissobrinho Builder overwrites the default middleware due to changes in the redirects. It also provides the `Admin` middleware for route level protection relative to roles.

* app/Http/Middleware/
    * Active.php
    * Admin.php
    * Permissions.php
    * RedirectIfAuthenticated.php
    * Roles.php

#### Requests
There are requests provided for handling the creation of Teams and updating of all components. Here we integrate the rules required that are able to run the validations and return errors. (If you're using Luissobrinho Builder FormMaker Facade then it will even handling accepting the errors and highlighting the appropriate fields.)

* app/Http/Requests/
    * PasswordUpdateRequest.php
    * RoleCreateRequest.php
    * TeamCreateRequest.php
    * TeamUpdateRequest.php
    * UserInviteRequest.php
    * UserUpdateRequest.php

#### Routes
Given that there are numerous routes added to handle teams, profiles, password etc all routes are overwritten with the starter kit.

* routes/web.php

#### Config
The permissions config file is published, this is a way for you to set access levels and types of permissions `Roles` can have

* config/permissions.php

#### Events
The events for various actions.

* app/Events/
    * UserRegisteredEmail.php

#### Listeners
The event listeners for various actions.

* app/Listeners/
    * UserRegisteredEmailListener.php

#### Models
Models are obvious, but when we then integrate Services below which handle all the buisness logic etc which make the calls to the models we implement SOLID practices, the Controller, Console or other Service, which calls the service only accesses the model through it. Once these have been integrated please ensure you delete the `User.php` model file and ensure that you have followed the installation and config instructions.

* app/Models/
    * UserMeta.php
    * User.php
    * Team.php
    * Role.php

#### Notifications
These are all our emails that we need to send out to the users in the application. These are amazing since they use the power of Laravel's notifcation component.

* app/Notficiations/
    * ActivateUserEmail.php
    * NewAccountEmail.php
    * ResetPasswordEmail.php

#### Services
Service structure allows us to keep the buisness logic outside of the models, and controllers. This approach is best suited for apps that may wish to integrate an API down the road or other things. It also allows for a highly testable structure to the application.

* app/Services/
    * Traits/
        * HasRoles.php
        * HasTeams.php
    * ActivateService.php
    * RoleService.php
    * TeamService.php
    * UserService.php

#### Database
Please ensure that all migrations and seeds are run post installation. These seeds set the default roles available in your application.

* database/factories/
    * RoleFactory.php
    * TeamFactory.php
    * UserFactory.php
    * UserMetaFactory.php
* database/migrations/
    * 2015_11_30_191713_create_user_meta_table.php
    * 2015_11_30_215038_create_roles_table.php
    * 2015_11_30_215040_create_role_user_table.php
    * 2015_12_04_155900_create_teams_table.php
    * 2015_12_04_155900_create_teams_users_table.php
* database/seeds/
    * DatabaseSeeder.php
    * RolesTableSeeder.php
    * UserTableSeeder.php

#### Views
The views consist of as little HTML as possible to perform the logical actions. These are intended to be the most basic, and all of which are intended to be modified.

* resources/views/
    * admin/
        * roles/
            * edit.blade.php
            * index.blade.php
            * invite.blade.php
        * users/
            * edit.blade.php
            * index.blade.php
            * invite.blade.php
        * dashboard.blade.php
    * auth/
        * activate/
            * email.blade.php
            * token.blade.php
        * passwords/
            * email.blade.php
            * reset.blade.php
        * login.blade.php
        * register.blade.php
    * errors/
        * 401.blade.php
        * 404.blade.php
        * 503.blade.php
    * partials/
        * errors.blade.php
        * message.blade.php
        * status.blade.php
    * team/
        * create.blade.php
        * edit.blade.php
        * index.blade.php
        * show.blade.php
    * user/
        * meta.blade.php
        * password.blade.php
        * settings.blade.php
    * dashboard.blade.php

#### Tests
Luissobrinho Builder starter kit provides the basic unit tests for each of its own parts. This provides some great examples of testing for building an application quickly.

* tests/
    * Feature/
        * TeamIntegrationTest.php
    * Unit/
        * UserServiceTest.php
        * TeamServiceTest.php
        * RoleServiceTest.php

## After Setup

### Dashboard access

The application dashboard is found by browsing to the /dashboard endpoint.
The default admin user login credentials are:

* email: admin@example.com
* password: admin

### User

The user model is expanded with Luissobrinho Builder Starter Kit. It adds to the basic user model: roles, teams, and user meta. The relationships are as follows:

* Meta: hasOne
* Roles: belongsToMany
* Team: belongsToMany

It also provides the following methods:

```
meta() // The relationship method
roles() // The relationship method
hasRole(role_name) // checks if user has role
teams() // The relationship method
isTeamMember(team_id) // checks if user is member
isTeamAdmin(team_id) // checks if user is admin level member
```

### Middleware

#### Admin
The Admin middleware acts as a tool for setting admin level permissions on the routes or controller level.

```
['middleware' => 'admin']
```

This simple addition to a route will ensure the user has access to the admin level, if not it will return them from where they came.

#### Active
The Active middleware acts checks if the account as been activated by accessing the activate url with the emailed token.

```
['middleware' => 'active']
```

This simple addition to a route will ensure the user has an activated account, if not it will redirect them to the /active page so they can request another activation token if necessary.

#### Roles
The Roles middleware allows you to set custom roles for your routes.

```
['middleware' => 'roles:admin|member']
```

#### Permissions
The Permissions middleware allows you to set custom permissions (a subset of roles) for your routes

```
['middleware' => 'permissions:admin|somethingDescriptive']
```

You can set permissions in the `config/permissions.php`

### Bootstrap UI

!!! Tip "Bootstrap Version 4"

If you feel like opting in for the Application starter kit. You also have a great bootstrapping option for the views. You can blast through the initial building of an app and hit the ground running!

>  You can use [LCrud](https://github.com/luissobrinho/lcrud) to create magical CRUDs for Laravel

```
php artisan luissobrinho:bootstrap
```

!!! Tip "This will also ensure that your webpack file is prepared to run"

#### What Boostrap Publishes

The command will overwrite any existing files with the bootstrap version of them:

* resources/views/
    * user/
        * meta.blade.php
        * password.blade.php
        * settings.blade.php
    * admin/
        * users/
            * edit.blade.php
            * index.blade.php
            * invite.blade.php
    * auth/
        * login.blade.php
        * password.blade.php
        * register.blade.php
        * reset.blade.php
    * dashboard/
        * main.blade.php
        * panel.blade.php
    * emails/
        * new-user.blade.php
        * password.blade.php
    * errors/
        * 404.blade.php
        * 503.blade.php
    * partials/
        * errors.blade.php
        * message.blade.php
    * team/
        * create.blade.php
        * edit.blade.php
        * index.blade.php
        * show.blade.php

### Application Activities

Sometimes its handy to know what your users are up to when they browse your site or application. The Activity kit gives you everything you need to track your users and their every action. The middleware does most of it for you, but your welcome to customize it to fit your needs.

#### Setup

```php
php artisan luissobrinho:activity
```

Add to your `config/app.php` the following:

```php
App\Providers\ActivityServiceProvider::class,
```

##### Facades
Provides the following tool for in app features:

```php
Activity::log($description);
Activity::getByUser($userId);
```

##### Helper

```php
activity($description) // will log the activity
```

### Application Features

Sometimes what we need is a simple way to toggle parts of an app on and off, or hey, maybe the client wants it. Either way, using the feature management kit can
take care of all that. Now you or your clients can toggle signups on an off, or any other features in the app. Just utilize the blade or helper components to take full control of your app.

#### Setup

```php
php artisan luissobrinho:features
```

You may want to add this line to your navigation:

```html
<li class="nav-item"><a class="nav-link" href="{!! url('admin/features') !!}"><span class="fa fa-cog"></span> Features</a></li>
```

Add to your `config/app.php` the following:

```php
App\Providers\FeatureServiceProvider::class,
```

Add this to the `app/Providers/RouteServiceProvider.php` in the `mapWebRoutes(Router $router)` function:

```php
require base_path('routes/features.php');
```

##### Facades
Provides the following tool for in app features:

```php
Features::isActive($key);
```

##### Blade

```blade
@feature($key)
// code goes here
@endfeature
```

##### Helper

```php
feature($key) // will return true|false
```

##### What Features Publishes:

The command will overwrite any existing files with the features version of them:

* app/Facades/Features.php
* app/Http/Controllers/Admin/FeatureController.php
* app/Http/Requests/FeatureCreateRequest.php
* app/Http/Requests/FeatureUpdateRequest.php
* app/Models/Feature.php
* app/Providers/FeatureServiceProvider.php
* app/Services/FeatureService.php
* database/migrations/2016_04_14_210036_create_features_table.php
* resources/views/admin/features/create.blade.php
* resources/views/admin/features/edit.blade.php
* resources/views/admin/features/index.blade.php
* routes/features.php
* tests/Feature/FeatureIntegrationTest.php
* tests/Unit/FeatureServiceTest.php

### Application Logs

The logs tool simply add a view of the app logs to your admin panel. This can be of assistance durring development or in keeping an application in check.

#### Setup

```php
php artisan luissobrinho:logs
```

You may want to add this line to your navigation:

```html
<li class="nav-item"><a href="{!! url('admin/logs') !!}"><span class="fa fa-line-chart"></span> Logs</a></li>
```

Add this to the `app/Providers/RouteServiceProvider.php` in the `mapWebRoutes(Router $router)` function:

```php
require base_path('routes/logs.php');
```

### Application Notifications

Luissobrinho Builder's notifications will build a basic controller, service, and views for both users and admins so you can easily notifiy your users, in bulk or specifically.

##### Setup

```php
php artisan luissobrinho:notifications
```

You may want to add this line to your navigation:

```html
<li><a href="{!! url('user/notifications') !!}"><span class="fa fa-envelope-o"></span> Notifications</a></li>
<li><a href="{!! url('admin/notifications') !!}"><span class="fa fa-envelope-o"></span> Notifications</a></li>
```

Add this to the `app/Providers/RouteServiceProvider.php` in the `mapWebRoutes(Router $router)` function:

```php
require base_path('routes/notification.php');
```

##### Facades
Provides the following tool for in app notifications:

```php
Notifications::notify($userId, $flag, $title, $details);
```

<small>Flags can be any bootstrap alert: default, info, success, warning, danger</small>

##### What Notifications Publishes:

The command will overwrite any existing files with the notification version of them:

* app/Facades/Notifications.php
* app/Http/Controllers/Admin/NotificationController.php
* app/Http/Controllers/User/NotificationController.php
* app/Http/Requests/NotificationRequest.php
* app/Models/Notification.php
* app/Services/NotificationService.php
* database/migrations/2016_04_14_180036_create_notifications_table.php
* resources/views/admin/notifications/create.blade.php
* resources/views/admin/notifications/edit.blade.php
* resources/views/admin/notifications/index.blade.php
* resources/views/notifications/index.blade.php
* resources/views/notifications/show.blade.php
* routes/notification.php
* tests/NotificationIntegrationTest.php
* tests/NotificationServiceTest.php

### Forge Integration

The FORGE component provides you with access to the FORGE API in your admin panel. Rather than having to log into FORGE for each adjustment, now
you can simply log into your own application and in the admin panel adjust the scheduler, or workers on your server configuration.

##### Requires
```php
composer require themsaid/forge-sdk
```

##### Setup

```php
php artisan luissobrinho:forge
```

You may want to add this line to your navigation:

```html
<li class="nav-item"><a href="{{ url('admin/forge/settings') }}"><span class="fa fa-fw fa-server"></span> Forge Settings</a></li>
<li class="nav-item"><a href="{{ url('admin/forge/scheduler') }}"><span class="fa fa-fw fa-calendar"></span> Forge Calendar</a></li>
<li class="nav-item"><a href="{{ url('admin/forge/workers') }}"><span class="fa fa-fw fa-cogs"></span> Forge Workers</a></li>
```

Add this to the `app/Providers/RouteServiceProvider.php` in the `mapWebRoutes(Router $router)` function:

You will see a line like: `->group(base_path('routes/web.php'));`

You need to change it to resemble this:
```php
->group(function () {
    require base_path('routes/web.php');
    require base_path('routes/forge.php');
}
```

Add these to the .env:
```php
FORGE_TOKEN=
FORGE_SERVER_ID=
FORGE_SITE_ID=
```

### Application API

If you feel like opting in for the Laracogs starter kit. You can also easily build in an API layer. Running the <code>luissobrinho:api</code> command will set up the bare bones components, but you can also use the API tools as a part of the CRUD now by using the <code>--api</code> option.

##### Requires
```php
composer require tymon/jwt-auth
```

##### Setup
```
php artisan luissobrinho:api
```

Essentially you want to do all the basic setup for JWT such as everything in here:
Then follow the directions regarding installation on: [https://github.com/tymondesigns/jwt-auth/wiki/Installation](https://github.com/tymondesigns/jwt-auth/wiki/Installation)

Add this to the `app/Providers/RouteServiceProvider.php` file in the `mapWebRoutes(Router $router)` function:
```php
require base_path('routes/api.php');
```

Add to the app/Http/Kernal.php under routeMiddleware:
```php
'jwt.auth' => \Tymon\JWTAuth\Middleware\GetUserFromToken::class,
'jwt.refresh' => \Tymon\JWTAuth\Middleware\RefreshToken::class,
```

Add to except attribute the app/Http/Middleware/VerifyCsrfToken.php (You also have to do this for CRUDs you add):
```php
'api/v1/login',
'api/v1/user/profile',
```

If you use Apache add this to the .htaccess file:
```php
RewriteEngine On
RewriteCond %{HTTP:Authorization} ^(.*)
RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
```

Also update your jwt config file and set the user to:

```php
\App\Models\User::class
```

##### What API publishes
The command will overwrite any existing files with the api version of them:

* app/Http/Controllers/Api/AuthController.php
* app/Http/Controllers/Api/UserController.php
* routes/api.php

### Application Queue

Horizon is amazing if you've got a redis instance configured and are running your queue through that, but not all apps need that nor do that have to start there.
If you've got a database driven queue and are looking for an easy management component to handle job retrying and cancellations then this will be a perfect
addition to your app.

##### Setup

```php
php artisan luissobrinho:queue
```

Add this to the `app/Providers/RouteServiceProvider.php` in the `mapWebRoutes(Router $router)` function:

```php
require base_path('routes/queue.php');
```

You may want to add this line to your navigation:

```html
<li><a href="{!! url('admin/queue') !!}"><span class="fa fa-list"></span> Queue</a></li>
```

### Social Media Logins

If you're looking to offer social media logins on your application and want a simple way to get started then look no further. Simply run the command and follow the steps below and you'll have GitHub login out of the box. Integrating Facebook etc afterward is easy when you look at the code base.

##### Requires
```php
composer require laravel/socialite
```

##### Setup
```
php artisan luissobrinho:socialite
```

The next step is to prepare your app with socialite:

Add this to your app config under providers:
```php
Laravel\Socialite\SocialiteServiceProvider::class
```

Add this to your app config under aliases:
```php
'Socialite' => Laravel\Socialite\Facades\Socialite::class,
```

Add `require base_path('routes/socialite.php');` to the `app/Providers/RouteServiceProvider.php` in the `mapWebRoutes(Router $router)` function:
```php
Route::middleware('web')
    ->namespace($this->namespace)
    ->group(function () {
        require base_path('routes/socialite.php');
        require base_path('routes/web.php');
    });
```

Finally set the access details in the services config:
```php
'github' => [
    'client_id' => 'id code',
    'client_secret' => 'secret code',
    'redirect' => 'http://your-domain/auth/github/callback',
    'scopes' => ['user:email'],
],
```

### Auditing

This package will help you understand changes in your Eloquent models, by providing information about possible discrepancies and anomalies that could indicate business concerns or suspect activities.

##### Requires
```php
composer require owen-it/laravel-auditing
```

Essentially you want to do all the basic setup for Laravel Auditing such as everything in here:
Then follow the directions regarding installation on: [http://www.laravel-auditing.com/docs/9.0/installation](http://www.laravel-auditing.com/docs/9.0/installation)

##### Setup
```
php artisan luissobrinho:auditing
```

##### What Auditing publishes
The command will overwrite any existing files with the auditing version of them:

* resources/lcrud/Model.txt
* app/Listeners/AuditedListener.php
* app/Listeners/AuditingListener.php

### Debugbar

This is a package to integrate the debugging bar for Laravel. You can publish assets and configure it through Laravel. Even when the bar is disabled in `APP_DEBUG = false` it is possible to enable it using the "Dev" permission placed by the Start package.

##### Requires
```php
composer require barryvdh/laravel-debugbar --dev
```

Essentially you want to do all the basic setup for Laravel Debugbar such as everything in here:
Then follow the directions regarding installation on: [https://github.com/barryvdh/laravel-debugbar](https://github.com/barryvdh/laravel-debugbar)

##### Setup
```
php artisan luissobrinho:debugbar
```

Insert the Middleware in the Kernel.php file of the $ middlewareGroups variable in the web index

```php
protected $middlewareGroups = [
    'web' => [
        // ...
        \App\Http\Middleware\DebugMiddleware::class
    ],
    
    'api' => [
        // ...
    ] 
];
```

###### Optional

Disable laravel debugging by changing the value of the `APP_DEBUG` variable to false in the `.env` file

```dotenv
APP_DEBUG=false
```

##### What DebugBar publishes
The command will overwrite any existing files with the debugbar version of them:

* app/Http/Middleware/DebugMiddleware.php

<hr>

## License
Luissobrinho Builder is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

### Bug Reporting and Feature Requests
Please add as many details as possible regarding submission of issues and feature requests

### Disclaimer
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
