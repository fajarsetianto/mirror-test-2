<?php

Breadcrumbs::for('responden.home', function ($trail) {
    $trail->push('Home', route('respondent.dashboard'));
});

Breadcrumbs::for('responden.home.form', function ($trail) {
    $trail->parent('responden.home');
    $trail->push('Form', route('respondent.form.index'));
});