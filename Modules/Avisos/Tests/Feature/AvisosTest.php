<?php

use Modules\Avisos\Models\Aviso;

uses(Tests\TestCase::class);

test('can see aviso list', function() {
    $this->authenticate();
   $this->get(route('app.avisos.index'))->assertOk();
});

test('can see aviso create page', function() {
    $this->authenticate();
   $this->get(route('app.avisos.create'))->assertOk();
});

test('can create aviso', function() {
    $this->authenticate();
   $this->post(route('app.avisos.store', [
       'name' => 'Joe',
       'email' => 'joe@joe.com'
   ]))->assertRedirect(route('app.avisos.index'));

   $this->assertDatabaseCount('avisos', 1);
});

test('can see aviso edit page', function() {
    $this->authenticate();
    $aviso = Aviso::factory()->create();
    $this->get(route('app.avisos.edit', $aviso->id))->assertOk();
});

test('can update aviso', function() {
    $this->authenticate();
    $aviso = Aviso::factory()->create();
    $this->patch(route('app.avisos.update', $aviso->id), [
        'name' => 'Joe Smith',
       'email' => 'joe@joe.com'
    ])->assertRedirect(route('app.avisos.index'));

    $this->assertDatabaseHas('avisos', ['name' => 'Joe Smith']);
});

test('can delete aviso', function() {
    $this->authenticate();
    $aviso = Aviso::factory()->create();
    $this->delete(route('app.avisos.delete', $aviso->id))->assertRedirect(route('app.avisos.index'));

    $this->assertDatabaseCount('avisos', 0);
});