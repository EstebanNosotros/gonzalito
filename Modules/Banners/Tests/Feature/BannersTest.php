<?php

use Modules\Banners\Models\Banner;

uses(Tests\TestCase::class);

test('can see banner list', function() {
    $this->authenticate();
   $this->get(route('app.banners.index'))->assertOk();
});

test('can see banner create page', function() {
    $this->authenticate();
   $this->get(route('app.banners.create'))->assertOk();
});

test('can create banner', function() {
    $this->authenticate();
   $this->post(route('app.banners.store', [
       'name' => 'Joe',
       'email' => 'joe@joe.com'
   ]))->assertRedirect(route('app.banners.index'));

   $this->assertDatabaseCount('banners', 1);
});

test('can see banner edit page', function() {
    $this->authenticate();
    $banner = Banner::factory()->create();
    $this->get(route('app.banners.edit', $banner->id))->assertOk();
});

test('can update banner', function() {
    $this->authenticate();
    $banner = Banner::factory()->create();
    $this->patch(route('app.banners.update', $banner->id), [
        'name' => 'Joe Smith',
       'email' => 'joe@joe.com'
    ])->assertRedirect(route('app.banners.index'));

    $this->assertDatabaseHas('banners', ['name' => 'Joe Smith']);
});

test('can delete banner', function() {
    $this->authenticate();
    $banner = Banner::factory()->create();
    $this->delete(route('app.banners.delete', $banner->id))->assertRedirect(route('app.banners.index'));

    $this->assertDatabaseCount('banners', 0);
});