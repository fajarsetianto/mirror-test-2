<?php

Breadcrumbs::for('officer.home', function ($trail) {
    $trail->push('Home', route('officer.dashboard'));
});

    Breadcrumbs::for('officer.home.monev.inspection', function ($trail) {
        $trail->parent('officer.home');
        $trail->push('Pemeriksaaan', route('officer.monev.inspection.index'));
    });

        Breadcrumbs::for('officer.home.monev.inspection.do', function ($trail,$officerTarget) {
            $trail->parent('officer.home.monev.inspection');
            $trail->push('Formulir', route('officer.monev.inspection.do.index', [$officerTarget->id]));
        });
            Breadcrumbs::for('officer.home.monev.inspection.do.question', function ($trail,$officerTarget, $instrument) {
                $trail->parent('officer.home.monev.inspection');
                $trail->push('Question', route('officer.monev.inspection.do.question.index', [$officerTarget->id, $instrument->id]));
            });