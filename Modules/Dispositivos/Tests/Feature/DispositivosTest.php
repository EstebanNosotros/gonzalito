<?php

use Modules\Dispositivos\Models\Dispositivo;

uses(Tests\TestCase::class);

test('can see dispositivo list', function() {
    $this->authenticate();
   $this->get(route('app.dispositivos.index'))->assertOk();
});

test('can see dispositivo create page', function() {
    $this->authenticate();
   $this->get(route('app.dispositivos.create'))->assertOk();
});

test('can create dispositivo', function() {
    $this->authenticate();
   $this->post(route('app.dispositivos.store', [
       'name' => 'Joe',
       'email' => 'joe@joe.com'
   ]))->assertRedirect(route('app.dispositivos.index'));

   $this->assertDatabaseCount('dispositivos', 1);
});

test('can see dispositivo edit page', function() {
    $this->authenticate();
    $dispositivo = Dispositivo::factory()->create();
    $this->get(route('app.dispositivos.edit', $dispositivo->id))->assertOk();
});

test('can update dispositivo', function() {
    $this->authenticate();
    $dispositivo = Dispositivo::factory()->create();
    $this->patch(route('app.dispositivos.update', $dispositivo->id), [
        'name' => 'Joe Smith',
       'email' => 'joe@joe.com'
    ])->assertRedirect(route('app.dispositivos.index'));

    $this->assertDatabaseHas('dispositivos', ['name' => 'Joe Smith']);
});

test('can delete dispositivo', function() {
    $this->authenticate();
    $dispositivo = Dispositivo::factory()->create();
    $this->delete(route('app.dispositivos.delete', $dispositivo->id))->assertRedirect(route('app.dispositivos.index'));

    $this->assertDatabaseCount('dispositivos', 0);
});