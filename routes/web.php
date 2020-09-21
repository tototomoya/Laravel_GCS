<?php
use Illuminate\Support\Facades\Route;

Route::get('/', 'UsersController@users_index');

Route::post('/login', 'UsersController@user_login');

Route::get('/{user_name}', 'UsersController@user_content');

Route::get('/register/{uuid}', 'UsersController@registerC');

Route::get('/register/{user_name}/{password}/{email}', 'UsersController@register');

Route::patch('/upload/{user_name}', 'FilesController@upload');

Route::get('/read/{user_name}/{file_id}', 'FilesController@read_file');

Route::patch('save/{user_name}/{file_id}', 'FilesController@save');

Route::delete('delete/{user_name}', 'FilesController@delete');
