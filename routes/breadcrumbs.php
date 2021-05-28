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

Breadcrumbs::for('target', function ($trail, $form) {
    $trail->parent('form', $form);
    $trail->push('Sasaran Monitoring', route('monev.form.target.index', [$form->id]));
});

Breadcrumbs::for('non-satuan', function ($trail) {
    $trail->parent('home');
    $trail->push('Manajemen Lembaga Non Satuan Pendidikan', route('institution.non-satuan.index'));
});

Breadcrumbs::for('indicator-report', function ($trail) {
    $trail->parent('home');
    $trail->push('Laporan Indikator', route('monev.indicator-report.index'));
});

Breadcrumbs::for('indicator-report.detail', function ($trail, $form) {
    $trail->parent('indicator-report');
    $trail->push(ucwords($form->name), route('monev.indicator-report.detail',[$form->id]));
});