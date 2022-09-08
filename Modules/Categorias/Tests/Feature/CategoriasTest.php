<?php

use Modules\Categorias\Models\Categoria;

uses(Tests\TestCase::class);

test('can see categoria list', function() {
    $this->authenticate();
   $this->get(route('app.categorias.index'))->assertOk();
});

test('can see categoria create page', function() {
    $this->authenticate();
   $this->get(route('app.categorias.create'))->assertOk();
});

test('can create categoria', function() {
    $this->authenticate();
   $this->post(route('app.categorias.store', [
       'name' => 'Joe',
       'email' => 'joe@joe.com'
   ]))->assertRedirect(route('app.categorias.index'));

   $this->assertDatabaseCount('categorias', 1);
});

test('can see categoria edit page', function() {
    $this->authenticate();
    $categoria = Categoria::factory()->create();
    $this->get(route('app.categorias.edit', $categoria->id))->assertOk();
});

test('can update categoria', function() {
    $this->authenticate();
    $categoria = Categoria::factory()->create();
    $this->patch(route('app.categorias.update', $categoria->id), [
        'name' => 'Joe Smith',
       'email' => 'joe@joe.com'
    ])->assertRedirect(route('app.categorias.index'));

    $this->assertDatabaseHas('categorias', ['name' => 'Joe Smith']);
});

test('can delete categoria', function() {
    $this->authenticate();
    $categoria = Categoria::factory()->create();
    $this->delete(route('app.categorias.delete', $categoria->id))->assertRedirect(route('app.categorias.index'));

    $this->assertDatabaseCount('categorias', 0);
});