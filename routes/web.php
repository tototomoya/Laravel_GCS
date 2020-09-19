<?php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Http\Request;

Route::get('/', 'UsersController@users_index');

Route::post('/login', 'UsersController@user_login');

Route::get('/{name}', 'UsersController@user_content');

Route::get('/register/{name}/{password}/{email}', function($name, $password, $email) {
    $base_url = "http://34.72.82.112:10500/";
    try {
        User::insert(
            ['name' => $name, 'password' => $password, 'email' => $email]
        );
        return '<a href="' . $base_url . $name . '">' . 'こちらがあなたのページです。</a>';
     } catch (Throwable $e) {
        report($e);
        preg_match('/(?<=\().*?(?=\))/', $name, $num);
        if ($num == []) {
            $num = array(0);
        }
        $num = $num[0] + 1;
        $name = preg_replace('/\(.*/', '', $name);
        $new_name = $name . "(" . (string)$num . ")";
        $new_url = $base_url . "register/" . $new_name . "/" . $password . "/" . $email . (string)$num;
        return "既に別の方がその名前を使用しています。</br>" . '<a href="' . $new_url . '">' . "こちらの名前なら利用できます。" . $new_name .  "</a>";
     }
});

Route::patch('/upload/{name}', 'FilesController@upload');

Route::get('/read/{user_name}/{file_id}', 'FilesController@read_file');

Route::patch('save/{user_name}/{file_id}', 'FilesController@save');

Route::delete('delete/{user_name}', 'FilesController@delete');
