<?php

Route::resource('groups', 'GroupController');
Route::resource('permissions', 'PermissionController');

Route::resource('groups.users', 'GroupUserController')->only(['store', 'destroy']);
Route::resource('groups.permissions', 'GroupPermissionController')->only(['store', 'destroy']);
