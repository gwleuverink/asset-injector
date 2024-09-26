# Auto Inject Package Assets

[![codestyle](https://github.com/gwleuverink/inject-package-assets/actions/workflows/codestyle.yml/badge.svg)](https://github.com/gwleuverink/inject-package-assets/actions/workflows/codestyle.yml)
[![tests](https://github.com/gwleuverink/inject-package-assets/actions/workflows/tests.yml/badge.svg)](https://github.com/gwleuverink/inject-package-assets/actions/workflows/tests.yml)

Simplify your Laravel package development with automatic asset injection.

This package allows you to seamlessly insert JavaScript and CSS into web responses without requiring manual inclusion by your package users 🚀

## Installation

Install the package via Composer:

```bash
composer require leuverink/asset-injector
```

## Usage

1. After installing, you'll need to create a `AssetInjector` implementation.

```php
namespace YourPackage;

use Leuverink\AssetInjector\Contracts\AssetInjector;


class InjectAssets implements AssetInjector
{
    // Used to identify your assets in the HTML response
    public function identifier(): string
    {
        return 'MY_PACKAGE';
    }

    // You can opt in to asset injection by implementing your own checks.
    // For example if a package user can control this via config file, or when your end user hits a middleware
    public function enabled(): bool
    {
        return true;
    }

    // Will inject return value in head tag or before html close if no head is present
    public function inject(): string
    {
        $js = file_get_contents(__DIR__ . '/../build/my-package.js');
        $css = file_get_contents(__DIR__ . '/../build/my-package.css');

        return <<< HTML
        <script type="module">{$js}</script>
        <style>{$css}</style>
        HTML;
    }
}
```

2. Register the implementation in your package's Service Provider.

```php
namespace YourPackage;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Leuverink\AssetInjector\AssetManager;
use YourPackage\InjectAssets;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        AssetManager::register(new InjectAssets);
    }
}
```

## How It Works

- Assets are automatically included in full-page responses (not partial HTML responses).
- If a `<head>` tag is present, assets are injected there. Otherwise, they're inserted before the closing `</html>` tag.
- The `identifier()` method helps prevent duplicate asset injection.
- Use the `enabled()` method to implement conditional asset injection based on your package's configuration.
- Customize the injected content by modifying the `inject()` method.

## Development

Use these commands for development:

```bash
composer lint # run all linters
composer fix # run all fixers

composer analyze # run static analysis
composer baseline # generate static analysis baseline

composer test # run test suite
```

## License

This package is open-source software licensed under the MIT license.
