<?php

use Leuverink\AssetInjector\Contracts\AssetInjector;

it('injects assets into head tag', function () {
    Route::get('test-inject-in-response', fn () => '<html><head></head></html>');

    $this->get('test-inject-in-response')
        ->assertOk()
        ->assertSee('<!--[TEST_PACKAGE ASSETS]-->', false);
});

it('injects assets into html body when no head tag is present', function () {
    Route::get('test-inject-in-response', fn () => '<html></html>');

    $this->get('test-inject-in-response')
        ->assertOk()
        ->assertSee('<!--[TEST_PACKAGE ASSETS]-->', false);
});

it('doesnt inject assets into responses without a closing html tag', function () {
    Route::get('test-inject-in-response', fn () => 'OK');

    $this->get('test-inject-in-response')
        ->assertOk()
        ->assertDontSee('<!--[TEST_PACKAGE ASSETS]-->', false);
});

it('doesnt inject assets when implementation returns false from enabled method', function () {
    $this->partialMock(AssetInjector::class)
        ->shouldReceive('enabled')->once()
        ->andReturn(false);

    Route::get('test-inject-in-response', fn () => '<html><head></head></html>');

    $this->get('test-inject-in-response')
        ->assertOk()
        ->assertDontSee('<!--[TEST_PACKAGE ASSETS]-->', false);
});
