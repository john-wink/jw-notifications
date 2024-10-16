# Filament Plugin for customizable Notification Channels per User

With this Plugin you can customize the Notifications Channel per User

## Installation

You can install the package via composer:

```bash
composer require john-wink/jw-notifications
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="jw-notifications-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="jw-notifications-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="jw-notifications-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
return $panel->plugins([
    JwNotificationsPlugin::make()
        ->notifications([
            OnboardingFormSubmittedNotification::class => 'customer',
            NewMessageReceived::class                     => 'customer',
            BirthdayNotification::class               => 'customer',
            LicenseExpiresNotification::class             => 'license',
            LicenseExpiredNotification::class             => 'license',
        ])
        ->channels(fn () => [
            'database'        => 'ToDo',
            FcmChannel::class => 'Push',
            'mail'            => 'Email',
        ]),
    ]);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [john-wink](https://github.com/john-wink)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
