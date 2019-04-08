<?php

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/cabinet', 'Cabinet\CabinetController@index')->name('cabinet');
