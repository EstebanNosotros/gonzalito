<?php

use Modules\Productos\Models\Producto;

uses(Tests\TestCase::class);

test('can see producto list', function() {
    $this->authenticate();
   $this->get(route('app.productos.index'))->assertOk();
});

test('can see producto create page', function() {
    $this->authenticate();
   $this->get(route('app.productos.create'))->assertOk();
});

test('can create producto', function() {
    $this->authenticate();
   $this->post(route('app.productos.store', [
       'name' => 'Joe',
       'email' => 'joe@joe.com'
   ]))->assertRedirect(route('app.productos.index'));

   $this->assertDatabaseCount('productos', 1);
});

test('can see producto edit page', function() {
    $this->authenticate();
    $producto = Producto::factory()->create();
    $this->get(route('app.productos.edit', $producto->id))->assertOk();
});

test('can update producto', function() {
    $this->authenticate();
    $producto = Producto::factory()->create();
    $this->patch(route('app.productos.update', $producto->id), [
        'name' => 'Joe Smith',
       'email' => 'joe@joe.com'
    ])->assertRedirect(route('app.productos.index'));

    $this->assertDatabaseHas('productos', ['name' => 'Joe Smith']);
});

test('can delete producto', function() {
    $this->authenticate();
    $producto = Producto::factory()->create();
    $this->delete(route('app.productos.delete', $producto->id))->assertRedirect(route('app.productos.index'));

    $this->assertDatabaseCount('productos', 0);
});