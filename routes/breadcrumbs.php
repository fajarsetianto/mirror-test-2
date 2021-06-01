<?php

// Admin Breadcrumb
Breadcrumbs::for('admin.home', function ($trail) {
    $trail->push('Home', '/');
});

    Breadcrumbs::for('admin.monev.forms', function ($trail) {
        $trail->parent('admin.home');
        $trail->push('Form', route('monev.form.index'));
    });

        Breadcrumbs::for('admin.monev.forms.form', function ($trail, $form) {
            $trail->parent('admin.monev.forms');
            $trail->push(ucwords($form->name), route('monev.form.instrument.index', [$form->id]));
        });

            Breadcrumbs::for('admin.monev.forms.form.question', function ($trail, $form, $instrument) {
                $trail->parent('admin.monev.forms.form',$form);
                $trail->push(ucwords($instrument->name), route('monev.form.instrument.question.index', [$form->id, $instrument->id]));
            });

            Breadcrumbs::for('admin.monev.forms.form.target', function ($trail, $form) {
                $trail->parent('admin.monev.forms.form', $form);
                $trail->push('Sasaran Monitoring', route('monev.form.target.index', [$form->id]));
            });

            Breadcrumbs::for('admin.monev.forms.form.preview', function ($trail, $form) {
                $trail->parent('admin.monev.forms.form', $form);
                $trail->push('Preview', route('monev.form.instrument.preview', [$form->id]));
            });

    Breadcrumbs::for('admin.monev.indicator-report', function ($trail) {
        $trail->parent('admin.home');
        $trail->push('Laporan Indikator', route('monev.indicator-report.index'));
    });

        Breadcrumbs::for('admin.monev.indicator-report.detail', function ($trail, $form) {
            $trail->parent('admin.monev.indicator-report');
            $trail->push(ucwords($form->name), route('monev.indicator-report.detail',[$form->id]));
        });

    Breadcrumbs::for('admin.monev.inspection', function ($trail) {
        $trail->parent('admin.home');
        $trail->push('Pemeriksaan', route('monev.inspection.index'));
    });

        Breadcrumbs::for('admin.monev.inspection.detail', function ($trail, $form) {
            $trail->parent('admin.monev.inspection');
            $trail->push(ucwords($form->name), route('monev.inspection.detail',[$form->id]));
        });

    Breadcrumbs::for('admin.monev.inspection-history', function ($trail) {
        $trail->parent('admin.home');
        $trail->push('Riwayat Pemeriksaan', route('monev.inspection-history.index'));
    });

        Breadcrumbs::for('admin.monev.inspection-history.target', function ($trail, $form) {
            $trail->parent('admin.monev.inspection-history');
            $trail->push(ucwords($form->name), route('monev.inspection.detail',[$form->id]));
        });

        Breadcrumbs::for('admin.monev.inspection-history.target.detail', function ($trail, $form, $target) {
            $trail->parent('admin.monev.inspection-history.targe',$form);
            $trail->push(ucwords($target->nonSatuanPendidikan->name), route('monev.inspection-history.target.detail',[$form->id, $target->id]));
        });

    Breadcrumbs::for('admin.institution.non-satuan', function ($trail) {
        $trail->parent('admin.home');
        $trail->push('Manajemen Lembaga Non Satuan Pendidikan', route('institution.non-satuan.index'));
    });

    Breadcrumbs::for('admin.management-user', function ($trail) {
        $trail->parent('admin.home');
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