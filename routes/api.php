<?php

Route::apiResource('groups', 'GroupController');
Route::apiResource('permissions', 'PermissionController');

Route::apiResource('groups.users', 'GroupUserController')->only(['store', 'destroy']);
Route::apiResource('groups.permissions', 'GroupPermissionController')->only(['store', 'destroy']);
