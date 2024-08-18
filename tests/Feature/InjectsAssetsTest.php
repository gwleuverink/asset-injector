<?php

use Leuverink\AssetInjector\Contracts\AssetInjector;

it('injects assets into response', function () {
    Route::get('test-inject-in-response', fn () => '<html><head></head></html>');

    $this->get('test-inject-in-response')
        ->assertOk()
        ->assertSee('<!--[TEST_PACKAGE]-->', false);
});

it('injects assets into head tag', function () {
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
    Route::get('test-inject-in-response', fn () => 'OK');

    $this->get('test-inject-in-response')
        ->assertOk()
        ->assertDontSee('<!--[TEST_PACKAGE]-->', false);
});

it('doesnt inject assets when implementation returns false from enabled method', function () {
    $this->partialMock(AssetInjector::class)
        ->shouldReceive('enabled')->once()
        ->andReturn(false);

    Route::get('test-inject-in-response', fn () => '<html><head></head></html>');

    $this->get('test-inject-in-response')
        ->assertOk()
        ->assertDontSee('<!--[TEST_PACKAGE]-->', false);
});
