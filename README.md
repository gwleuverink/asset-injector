# Auto inject package assets

[![codestyle](https://github.com/gwleuverink/inject-assets/actions/workflows/codestyle.yml/badge.svg)](https://github.com/gwleuverink/inject-assets/actions/workflows/codestyle.yml)
[![tests](https://github.com/gwleuverink/inject-assets/actions/workflows/tests.yml/badge.svg)](https://github.com/gwleuverink/inject-assets/actions/workflows/tests.yml)

No need to ask you package user to manually include any scripts or styles. Automatically inject them in the response instead 🚀

## Installation

```bash
composer require leuverink/inject-assets
```

## Usage

After installing, you need to bind a concrete implementation to the AssetInjector interface in your packages Service Provider.

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

And ofcource implement that class.

```php
namespace YourPackage;

use Leuverink\InjectAssets\Contracts\AssetInjector;


class InjectAssets implements AssetInjector
{
    // Used to determine if assets were already injected in the response
    public string $identifier = 'MY_PACKAGE';

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

## Configuration

## Development

```bash
composer lint # run all linters
composer fix # run all fixers

composer analyze # run static analysis
composer baseline # generate static analysis baseline

composer test # run test suite
```