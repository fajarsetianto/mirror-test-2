<?php

Breadcrumbs::for('responden.home', function ($trail) {
    $trail->push('Home', route('respondent.dashboard'));
});

    Breadcrumbs::for('responden.home.form', function ($trail) {
        $trail->parent('responden.home');
        $trail->push('Form', route('respondent.form.index'));
    });

        Breadcrumbs::for('responden.home.form.question', function ($trail, $instrument) {
            $trail->parent('responden.home.form');
            $trail->push('Question', route('respondent.form.question.index',[$instrument->id]));
        });