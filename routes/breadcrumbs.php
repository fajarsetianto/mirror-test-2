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

Breadcrumbs::for('inspection', function ($trail) {
    $trail->parent('home');
    $trail->push('Pemeriksaan', route('monev.inspection.index'));
});

Breadcrumbs::for('inspection.detail', function ($trail, $form) {
    $trail->parent('inspection');
    $trail->push(ucwords($form->name), route('monev.inspection.detail',[$form->id]));
});

Breadcrumbs::for('inspection-history.target', function ($trail, $form) {
    $trail->parent('inspection');
    $trail->push(ucwords($form->name), route('monev.inspection.detail',[$form->id]));
});

Breadcrumbs::for('inspection-history.target.detail', function ($trail, $form, $target) {
    $trail->parent('inspection-history.target',$form);
    $trail->push(ucwords($target->nonSatuanPendidikan->name), route('monev.inspection-history.target.detail',[$form->id, $target->id]));
});

Breadcrumbs::for('management-user', function ($trail) {
    $trail->parent('home');
    $trail->push('Manajemen User', route('management-user.index'));
});


// Breadcrumb for Respondent
Breadcrumbs::for('responden.home', function ($trail) {
    $trail->push('Home', route('respondent.dashboard'));
});

Breadcrumbs::for('responden.home.form', function ($trail) {
    $trail->parent('responden.home');
    $trail->push('Form', route('respondent.form.index'));
});