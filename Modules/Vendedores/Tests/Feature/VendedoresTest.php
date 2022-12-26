<?php

use Modules\Vendedores\Models\Vendedore;

uses(Tests\TestCase::class);

test('can see vendedore list', function() {
    $this->authenticate();
   $this->get(route('app.vendedores.index'))->assertOk();
});

test('can see vendedore create page', function() {
    $this->authenticate();
   $this->get(route('app.vendedores.create'))->assertOk();
});

test('can create vendedore', function() {
    $this->authenticate();
   $this->post(route('app.vendedores.store', [
       'name' => 'Joe',
       'email' => 'joe@joe.com'
   ]))->assertRedirect(route('app.vendedores.index'));

   $this->assertDatabaseCount('vendedores', 1);
});

test('can see vendedore edit page', function() {
    $this->authenticate();
    $vendedore = Vendedore::factory()->create();
    $this->get(route('app.vendedores.edit', $vendedore->id))->assertOk();
});

test('can update vendedore', function() {
    $this->authenticate();
    $vendedore = Vendedore::factory()->create();
    $this->patch(route('app.vendedores.update', $vendedore->id), [
        'name' => 'Joe Smith',
       'email' => 'joe@joe.com'
    ])->assertRedirect(route('app.vendedores.index'));

    $this->assertDatabaseHas('vendedores', ['name' => 'Joe Smith']);
});

test('can delete vendedore', function() {
    $this->authenticate();
    $vendedore = Vendedore::factory()->create();
    $this->delete(route('app.vendedores.delete', $vendedore->id))->assertRedirect(route('app.vendedores.index'));

    $this->assertDatabaseCount('vendedores', 0);
});