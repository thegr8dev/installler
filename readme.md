
# Installation :

```
composer require mediacity/installer
```

## Publish all assets after installing the package by running this command :

```
php artisan vendor:publish --tag=installer
```

After that add 'is_install' middleware to your routes middleware groups example below : 

```
    Route::group(['middleware' => ['is_install']], function () {

        // your other routes are now bind with installer middleware

    });

```

You can configure your envato app_id, required php version in config/installer.php

Done 




