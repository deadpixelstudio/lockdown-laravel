<?php

Route::resource('groups', 'GroupController');
Route::resource('permissions', 'PermissionController');

Route::resource('groups.users', 'GroupUserController')->only(['store', 'destroy']);
