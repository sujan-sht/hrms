<?php


Route::group(['prefix' => 'admin', 'middleware' => ['auth','web','XssSanitizer']], function () {

    
    Route::get('Notification', ['as' => 'Notification.index', 'uses' => 'NotificationController@index']);

    
    Route::get('Notification/checkLink', ['as' => 'Notification.checkLink', 'uses' => 'NotificationController@checkLink']);
    Route::get('Notification/markAsReadAll', ['as' => 'Notification.markAsReadAll', 'uses' => 'NotificationController@markAsReadAll']);

});