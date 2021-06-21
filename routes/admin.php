<?php
use App\Http\Controllers\NotificationController;

Route::group(['prefix' => 'notification', 'as'=> 'notification.'], function(){
    Route::get('/read/{notification}',[NotificationController::class, 'read'])->name('read');
    Route::get('/markallread/{guard}',[NotificationController::class, 'markAllRead'])->name('markallread');
});

Route::group(['prefix' => 'monitoring-evaluasi','as' => 'monev.'], function(){
    Route::group(['prefix' => 'form','as' => 'form.'],function(){
        Route::get('/', 'FormController@index')->name('index');
        Route::get('/data', 'FormController@data')->name('data');
        Route::get('/create', 'FormController@create')->name('create');
        Route::post('/create', 'FormController@store')->name('store');

        Route::group(['middleware' => 'can:manage,form'], function(){
            Route::get('{form}/edit', 'FormController@edit')->name('edit');
            Route::get('{form}/publish', 'FormController@publish')->name('publish');
            Route::post('{form}/publish', 'FormController@publishing')->name('publishing');
            Route::put('{form}/update', 'FormController@update')->name('update');
            Route::delete('{form}/', 'FormController@destroy')->name('destroy');
    
            Route::group(['prefix' => '{form}/instruments','as' => 'instrument.'],function(){
                Route::get('/', 'InstrumentController@index')->name('index');
                Route::get('preview', 'InstrumentController@preview')->name('preview');
                Route::get('/data', 'InstrumentController@data')->name('data');
                Route::get('/create', 'InstrumentController@create')->name('create');
                Route::post('/create', 'InstrumentController@store')->name('store');
                Route::post('/reorder', 'InstrumentController@reorder')->name('reorder');
                Route::get('{instrument}/edit', 'InstrumentController@edit')->name('edit');
                Route::put('{instrument}/update', 'InstrumentController@update')->name('update');
                Route::delete('{instrument}', 'InstrumentController@destroy')->name('destroy');
    
                Route::group(['prefix' => '{instrument}/question','as' => 'question.'],function(){
                    Route::get('/', 'QuestionController@index')->name('index');
                    Route::get('/data', 'QuestionController@data')->name('data');
                    Route::get('/create', 'QuestionController@create')->name('create');
                    Route::post('/create', 'QuestionController@store')->name('store');
                    Route::post('/changestatus', 'QuestionController@changestatus')->name('changestatus');
                    Route::get('{question}/edit', 'QuestionController@edit')->name('edit');
                    Route::put('{question}/update', 'QuestionController@update')->name('update');
                    Route::delete('{question}', 'QuestionController@destroy')->name('destroy');
                });
            });
            Route::group(['prefix' => '{form}/indicators','as' => 'indicator.'],function(){
                // Route::get('/', 'Idicator@index')->name('index');
                Route::get('/data', 'IndicatorController@data')->name('data');
                Route::get('/create', 'IndicatorController@create')->name('create');
                Route::post('/create', 'IndicatorController@store')->name('store');
                Route::get('{indicator}/edit', 'IndicatorController@edit')->name('edit');
                Route::put('{indicator}/update', 'IndicatorController@update')->name('update');
                Route::delete('{indicator}', 'IndicatorController@destroy')->name('destroy');
            });
    
            Route::group(['prefix' => '{form}/sasaran-monitoring','as' => 'target.'],function(){
                Route::get('/', 'TargetController@index')->name('index');
                Route::get('/data', 'TargetController@data')->name('data');
                Route::get('/create', 'TargetController@create')->name('create');
                Route::get('/input/{target?}', 'TargetController@getInput')->name('input');
                Route::post('/create', 'TargetController@store')->name('store');
                Route::get('{target}/edit', 'TargetController@edit')->name('edit');
                Route::put('{target}/update', 'TargetController@update')->name('update');
                Route::get('summary', 'TargetController@summary')->name('summary');
                Route::delete('{target}', 'TargetController@destroy')->name('destroy');
            });
        });
       
    });

    Route::group(['namespace' => 'Inspection'], function(){
        Route::group(['prefix' => 'pemeriksaan','as' => 'inspection.'], function(){
            Route::get('/', 'InspectionController@index')->name('index');
            Route::get('/data', 'InspectionController@data')->name('data');
            Route::group(['prefix' => '{form}', 'as' => 'form.', 'middleware' => 'can:manage,form'], function(){
                Route::get('/', 'TargetController@index')->name('index');
                Route::get('data', 'TargetController@data')->name('data');
                Route::get('/sasaran-monitoring/{target}', 'TargetController@detail')->name('detail');
                Route::group(['prefix' => '{target}', 'as' => 'instrument.'], function(){
                    Route::get('data', 'InstrumentController@data')->name('data');
                });
            });
        });
    
        Route::group(['namespace' => 'History','prefix' => 'riwayat-pemeriksaan','as' => 'inspection-history.'], function(){
            Route::get('/', 'InspectionHistoryController@index')->name('index');
            Route::get('/data', 'InspectionHistoryController@data')->name('data');
            Route::group(['prefix' => '{form}', 'as' => 'form.', 'middleware' => 'can:manage,form'], function(){
                Route::get('/', 'TargetController@index')->name('index');
                Route::get('data', 'TargetController@data')->name('data');
                Route::get('/sasaran-monitoring/{target}', 'TargetController@detail')->name('detail');
                Route::group(['prefix' => '{target}', 'as' => 'instrument.'], function(){
                    Route::get('data', 'InstrumentController@data')->name('data');
                });
            });
        });
    });
    
    Route::group(['prefix' => 'laporan-indikator','as' => 'indicator-report.'], function(){
        Route::get('/', 'IndicatorReportController@index')->name('index');
        Route::get('/data', 'IndicatorReportController@data')->name('data');
        Route::group(['middleware' => 'can:manage,form'], function(){
            Route::get('{form}/detail', 'IndicatorReportController@detail')->name('detail');
            Route::get('{form}/{indicator}', 'IndicatorReportController@detailIndicator')->name('detail.indicator');
            Route::get('{form}/{indicator}/data', 'IndicatorReportController@detailIndicatorData')->name('detail.indicator.data');
        });
    });
});

Route::group(['prefix' => 'management-lembaga','as' => 'institution.'], function(){
    Route::group(['prefix' => 'satuan-pendidikan','as' => 'satuan.'], function(){
        Route::get('/', 'EducationalInstitutionController@index')->name('index');
        Route::get('/data', 'EducationalInstitutionController@data')->name('data');
        Route::get('/select2', 'EducationalInstitutionController@select2')->name('select2');
    });
    Route::group(['prefix' => 'non-satuan-pendidikan','as' => 'non-satuan.'], function(){
        Route::get('/', 'NonEducationalInstitutionController@index')->name('index');
        Route::get('/data', 'NonEducationalInstitutionController@data')->name('data');
        Route::get('/select2', 'NonEducationalInstitutionController@select2')->name('select2');
        Route::get('/create', 'NonEducationalInstitutionController@create')->name('create');
        Route::post('/create', 'NonEducationalInstitutionController@store')->name('store');

        Route::group(['middleware' => 'can:manage,nonEducationalInstitution'], function(){
            Route::get('{nonEducationalInstitution}/edit', 'NonEducationalInstitutionController@edit')->name('edit');
            Route::put('{nonEducationalInstitution}/update', 'NonEducationalInstitutionController@update')->name('update');
            Route::delete('{nonEducationalInstitution}', 'NonEducationalInstitutionController@destroy')->name('destroy');
        });
        
    });
});

Route::group(['prefix' => 'management-user','as' => 'management-user.'], function(){
    Route::get('/', 'OfficerController@index')->name('index');
    Route::get('/data', 'OfficerController@data')->name('data');
    Route::get('/create', 'OfficerController@create')->name('create');
    Route::get('/select2', 'OfficerController@select2')->name('select2');
    Route::post('/create', 'OfficerController@store')->name('store');

    Route::group(['middleware' => 'can:manage,officer'],function(){
        Route::get('{officer}/edit', 'OfficerController@edit')->name('edit');
        Route::put('{officer}/update', 'OfficerController@update')->name('update');
        Route::delete('{officer}', 'OfficerController@destroy')->name('destroy');
    });
    
});