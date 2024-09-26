<?php

use Tests\Stubs\Implement;
use Tests\Stubs\DisabledImplement;
use Leuverink\AssetInjector\AssetManager;

it('injects assets into response', function () {
    AssetManager::register(new Implement);

    Route::get('test-inject-in-response', fn () => '<html><head></head></html>');

    $this->get('test-inject-in-response')
        ->assertOk()
        ->assertSee('<!--[TEST_PACKAGE]-->', false);
});

it('injects assets into head tag', function () {
    AssetManager::register(new Implement);

    Route::get('test-inject-in-response', fn () => '<html><head></head></html>');

    $expected = <<< 'HTML'
    <html><head>
    <!--[TEST_PACKAGE]-->
    TEST_PACKAGE_ASSETS_INJECTED
    <!--[ENDTEST_PACKAGE]-->
    </head></html>
    HTML;

    $this->get('test-inject-in-response')
        ->assertOk()
        ->assertSee($expected, false);
});

it('injects assets into html body when no head tag is present', function () {
    AssetManager::register(new Implement);

    Route::get('test-inject-in-response', fn () => '<html></html>');

    $expected = <<< 'HTML'
    <html>
    <!--[TEST_PACKAGE]-->
    TEST_PACKAGE_ASSETS_INJECTED
    <!--[ENDTEST_PACKAGE]-->
    </html>
    HTML;

    $this->get('test-inject-in-response')
        ->assertOk()
        ->assertSee($expected, false);
});

it('injects assets into the end of the html body when no head tag is present', function () {
    AssetManager::register(new Implement);

    Route::get('test-inject-in-response', fn () => '<html><p>Hello World</p></html>');

    $expected = <<< 'HTML'
    <html><p>Hello World</p>
    <!--[TEST_PACKAGE]-->
    TEST_PACKAGE_ASSETS_INJECTED
    <!--[ENDTEST_PACKAGE]-->
    </html>
    HTML;

    $this->get('test-inject-in-response')
        ->assertOk()
        ->assertSee($expected, false);
});

it('doesnt inject assets into responses without a closing html tag', function () {
    AssetManager::register(new Implement);

    Route::get('test-inject-in-response', fn () => 'OK');

    $this->get('test-inject-in-response')
        ->assertOk()
        ->assertDontSee('<!--[TEST_PACKAGE]-->', false);
});

it('doesnt inject assets when implementation returns false from enabled method', function () {
    AssetManager::register(new Implement);
    AssetManager::register(new DisabledImplement);

    Route::get('test-inject-in-response', fn () => '<html><head></head></html>');

    $this->get('test-inject-in-response')
        ->assertOk()
        ->assertSee('<!--[TEST_PACKAGE]-->', false)
        ->assertDontSee('<!--[DISABLED_TEST_PACKAGE]-->', false);
});

it('can inject more than one implement', function () {
    AssetManager::register(new Implement);
    AssetManager::register(new Implement);

    Route::get('test-inject-in-response', fn () => '<html><head></head></html>');

    $this->get('test-inject-in-response')
        ->assertOk()
        ->assertSee(<<<'HTML'
        <!--[TEST_PACKAGE]-->
        TEST_PACKAGE_ASSETS_INJECTED
        <!--[ENDTEST_PACKAGE]-->

        <!--[TEST_PACKAGE]-->
        TEST_PACKAGE_ASSETS_INJECTED
        <!--[ENDTEST_PACKAGE]-->
        HTML, false);
});
