<?php

it('injects assets into head tag', function () {
    Route::get('test-inject-in-response', fn () => '<html><head></head></html>');

    $this->get('test-inject-in-response')
        ->assertOk()
        ->assertSee('<!--[MAGIC_TODO-ASSETS]-->', false);
})->todo();

it('injects assets into html body when no head tag is present', function () {
    Route::get('test-inject-in-response', fn () => '<html></html>');

    $this->get('test-inject-in-response')
        ->assertOk()
        ->assertSee('<!--[MAGIC_TODO-ASSETS]-->', false);
})->todo();

it('doesnt inject assets into responses without a closing html tag', function () {
    Route::get('test-inject-in-response', fn () => 'OK');

    $this->get('test-inject-in-response')
        ->assertOk()
        ->assertDontSee('<!--[MAGIC_TODO-ASSETS]-->', false);
})->todo();
