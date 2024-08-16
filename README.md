# Auto inject package assets

[![codestyle](https://github.com/gwleuverink/inject-package-assets/actions/workflows/codestyle.yml/badge.svg)](https://github.com/gwleuverink/inject-package-assets/actions/workflows/codestyle.yml)
[![tests](https://github.com/gwleuverink/inject-package-assets/actions/workflows/tests.yml/badge.svg)](https://github.com/gwleuverink/inject-package-assets/actions/workflows/tests.yml)

No need to ask your package users to manually include any scripts or styles. Automatically inject them in the response instead 🚀

## Installation

```bash
composer require leuverink/inject-assets
```

## Usage

After installing, you'll need to create a class that implements the `AssetInjector` interface.

```php
namespace YourPackage;

use Leuverink\InjectAssets\Contracts\AssetInjector;


class InjectAssets implements AssetInjector
{
    // Used to determine if assets were already injected in the response
    public function identifier(): string
    {
        return 'MY_PACKAGE';
    }

    // You can opt in to asset injection by implementing your own checks.
    // For example if a package user can control this via config file.
    public function enabled(): bool
    {
        return true;
    }

    // Will inject return value in head tag or befor html close if no head is present
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

This serves as an example. You may return any string you like from the `inject` method.

Afterward you need to bind a concrete implementation to the AssetInjector interface in your packages Service Provider.

```php
namespace YourPackage;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Leuverink\InjectAssets\Contracts\AssetInjector;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->bind(
            AssetInjector::class,
            fn() => new \YourPackage\InjectAssets
        );
    }
}
```

That's it. The assets will be included in every full-page response (not in partial html responses).

When the response contains a head tag your assets will be injected in there. When there is no head tag they will be injected before the end of the closing html tag.

## Development

```bash
composer lint # run all linters
composer fix # run all fixers

composer analyze # run static analysis
composer baseline # generate static analysis baseline

composer test # run test suite
```
