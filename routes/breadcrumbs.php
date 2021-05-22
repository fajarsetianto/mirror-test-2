<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', '/');
});

Breadcrumbs::for('forms', function ($trail) {
    $trail->parent('home');
    $trail->push('Form', route('monev.form.index'));
});

Breadcrumbs::for('form', function ($trail, $form) {
    $trail->parent('forms');
    $trail->push(ucwords($form->name), route('monev.form.instrument.index', [$form->id]));
    
});