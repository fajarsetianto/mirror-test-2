<?php
Breadcrumbs::for('admin.home', function ($trail) {
    $trail->push('Home', '/');
});

    Breadcrumbs::for('admin.monev.forms', function ($trail) {
        $trail->parent('admin.home');
        $trail->push('Form', route('admin.monev.form.index'));
    });

        Breadcrumbs::for('admin.monev.forms.form', function ($trail, $form) {
            $trail->parent('admin.monev.forms');
            $trail->push(ucwords($form->name), route('admin.monev.form.instrument.index', [$form->id]));
        });

            Breadcrumbs::for('admin.monev.forms.form.question', function ($trail, $form, $instrument) {
                $trail->parent('admin.monev.forms.form',$form);
                $trail->push(ucwords($instrument->name), route('admin.monev.form.instrument.question.index', [$form->id, $instrument->id]));
            });

            Breadcrumbs::for('admin.monev.forms.form.target', function ($trail, $form) {
                $trail->parent('admin.monev.forms.form', $form);
                $trail->push('Sasaran Monitoring', route('admin.monev.form.target.index', [$form->id]));
            });

            Breadcrumbs::for('admin.monev.forms.form.preview', function ($trail, $form) {
                $trail->parent('admin.monev.forms.form', $form);
                $trail->push('Preview', route('admin.monev.form.instrument.preview', [$form->id]));
            });

    Breadcrumbs::for('admin.monev.indicator-report', function ($trail) {
        $trail->parent('admin.home');
        $trail->push('Laporan Indikator', route('admin.monev.indicator-report.index'));
    });

        Breadcrumbs::for('admin.monev.indicator-report.detail', function ($trail, $form) {
            $trail->parent('admin.monev.indicator-report');
            $trail->push(ucwords($form->name), route('admin.monev.indicator-report.detail',[$form->id]));
        });

    Breadcrumbs::for('admin.monev.inspection', function ($trail) {
        $trail->parent('admin.home');
        $trail->push('Pemeriksaan', route('admin.monev.inspection.index'));
    });

        Breadcrumbs::for('admin.monev.inspection.form.index', function ($trail, $form) {
            $trail->parent('admin.monev.inspection');
            $trail->push(ucwords($form->name), route('admin.monev.inspection.form.index',[$form->id]));
        });

    Breadcrumbs::for('admin.monev.inspection-history', function ($trail) {
        $trail->parent('admin.home');
        $trail->push('Riwayat Pemeriksaan', route('admin.monev.inspection-history.index'));
    });

        Breadcrumbs::for('admin.monev.inspection-history.form', function ($trail, $form) {
            $trail->parent('admin.monev.inspection-history');
            $trail->push(ucwords($form->name), route('admin.monev.inspection.form.index',[$form->id]));
        });

        Breadcrumbs::for('admin.monev.inspection-history.form.detail', function ($trail, $form, $target) {
            $trail->parent('admin.monev.inspection-history.form',$form);
            $trail->push(ucwords($target->institutionable->name), route('admin.monev.inspection-history.form.detail',[$form->id, $target->id]));
        });

    Breadcrumbs::for('admin.institution.non-satuan', function ($trail) {
        $trail->parent('admin.home');
        $trail->push('Manajemen Lembaga Non Satuan Pendidikan', route('admin.institution.non-satuan.index'));
    });

    Breadcrumbs::for('admin.management-user', function ($trail) {
        $trail->parent('admin.home');
        $trail->push('Manajemen User', route('admin.management-user.index'));
    });
