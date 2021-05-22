<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', '/');
});

Breadcrumbs::for('form', function ($trail) {
    $trail->parent('home');
    $trail->push('Form', route('monev.form.index'));
});

Breadcrumbs::for('instrument', function ($trail) {
    $trail->parent('form');
    $trail->push('Form', route('monev.form.index'));
    
});