# Ucloud ufile storage for laravel
base on https://docs.ucloud.cn/api-docs/ufile-api/ 

# Usage:
register the `Xujif\UcloudUfileStorage\UfileServiceProvider::class;` in your app configuration file:
```php
'providers' => [
    // Other service providers...
    Xujif\UcloudUfileStorage\UfileServiceProvider::class,
],
```
config
```
[
    'ucloud-ufile'=>[
        'bucket'=>'xxx',
        'public_key'=>'xxx',
        'secret_key'=>'xxx',
        'suffix'=>'',
        'prefix'=>'',
    ]
]
```
