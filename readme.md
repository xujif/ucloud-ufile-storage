#ucloud ufile storage for laravel
base on https://docs.ucloud.cn/api-docs/ufile-api/ 

#use:
register the `Xujif\UcloudUfileStorage\UfileServiceProvider::class;` in your app configuration file:
```php
'providers' => [
    // Other service providers...
    Xujif\UcloudUfileStorage\UfileServiceProvider::class,
],
```
