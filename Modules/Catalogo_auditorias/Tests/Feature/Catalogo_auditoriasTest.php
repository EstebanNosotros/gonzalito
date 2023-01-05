<?php

use Modules\Catalogo_auditorias\Models\Catalogo_auditoria;

uses(Tests\TestCase::class);

test('can see catalogo_auditoria list', function() {
    $this->authenticate();
   $this->get(route('app.catalogo_auditorias.index'))->assertOk();
});

test('can see catalogo_auditoria create page', function() {
    $this->authenticate();
   $this->get(route('app.catalogo_auditorias.create'))->assertOk();
});

test('can create catalogo_auditoria', function() {
    $this->authenticate();
   $this->post(route('app.catalogo_auditorias.store', [
       'name' => 'Joe',
       'email' => 'joe@joe.com'
   ]))->assertRedirect(route('app.catalogo_auditorias.index'));

   $this->assertDatabaseCount('catalogo_auditorias', 1);
});

test('can see catalogo_auditoria edit page', function() {
    $this->authenticate();
    $catalogo_auditoria = Catalogo_auditoria::factory()->create();
    $this->get(route('app.catalogo_auditorias.edit', $catalogo_auditoria->id))->assertOk();
});

test('can update catalogo_auditoria', function() {
    $this->authenticate();
    $catalogo_auditoria = Catalogo_auditoria::factory()->create();
    $this->patch(route('app.catalogo_auditorias.update', $catalogo_auditoria->id), [
        'name' => 'Joe Smith',
       'email' => 'joe@joe.com'
    ])->assertRedirect(route('app.catalogo_auditorias.index'));

    $this->assertDatabaseHas('catalogo_auditorias', ['name' => 'Joe Smith']);
});

test('can delete catalogo_auditoria', function() {
    $this->authenticate();
    $catalogo_auditoria = Catalogo_auditoria::factory()->create();
    $this->delete(route('app.catalogo_auditorias.delete', $catalogo_auditoria->id))->assertRedirect(route('app.catalogo_auditorias.index'));

    $this->assertDatabaseCount('catalogo_auditorias', 0);
});