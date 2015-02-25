This Laravel 5 package with a collection of Helpers and blade extensions for my gulp asset pipeline

This package assumes that your asset pipeline:
- 


##Installation
The package can be installed through Composer:

```
composer require ammonkc/laravel-basket
```

This service provider must be installed:

```php

//for laravel <=4.2: app/config/app.php

'providers' => [
    ...
    'Ammonkc\BonzaiHelper\BonzaiHelperServiceProvider'
    ...
];
```

This package also comes with a facade, which provides an easy way to call the the functionality.


```php

//for laravel <=4.2: app/config/app.php

'aliases' => array(
    ...
    'Bonzai' => 'Ammonkc\BonzaiHelper\BonzaiHelperFacade',
    ...
)
```


##Configuration
You can publish the configuration file using this command:
```console
php artisan config:publish ammonkc/bonzai-helper
```

A configuration-file with some sensible defaults will be placed in your config/packages directory:

```php
return
[
    /**
     * The url that points to the directory were your assets are stored
     *
     */
    'assetDir'         =>  'assets',
];
```
