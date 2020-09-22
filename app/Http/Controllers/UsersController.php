<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\File;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use App\Mail\SendTestMail;
use Mail;
use Illuminate\Support\Facades\Cache;

class UsersController extends Controller
{
    public function user_content(Request $request, $user_name) {
        $owner = session('owner') == $user_name ? 'あなたはオーナーです。' : 'あなたはオーナーじゃないです。';
        $user = Cache::rememberForever('user', function() use ($user_name){
            return User::where('name', $user_name)->first();
        });
        $files = @$user->files;
        if($files) {
            return view('user', ['user' => $user, 'files' => $files, 'owner' => $owner]);
        } else {
            return view('user', ['user' => $user, 'files' => [], 'owner' => $owner]);
        }
    }

    public function users_index() {
        $owner = session('owner');
        if(!$owner) {
            return view('user_login');
        } else {
            $users = User::simplePaginate(15);
            return view('users_index', ['users' => $users]);
        }
    }

    public function registerC(Request $request, $uuid){
        $session = $request->session();
        if($session->remove('uuid') == $uuid) {
            User::insert(
                ['name' => session('user_name'), 'password' => session('password'), 'email' => session('email')]
            );
            Cache::forever('users_name', User::pluck('name'));
            return '<a href="' . url("/") . "/" . session('user_name') . '">' . 'こちらがあなたのページです。</a>';
        } else {
            return '現在このURLは使えません。';
        }
    }

    public function register($user_name, $password, $email) {
        $users = Cache::rememberForever('users_name', function() {
            return User::pluck('name');
        });
        if(!$users->contains($user_name)) {
            $uuid = uniqid();
            session(['uuid' => $uuid, 'user_name' => $user_name, 'password' => $password, 'email' => $email, 'owner' => $user_name]);
            $to = [
                [
                    'email' => $email,
                    'name' => $user_name,
                ]
            ];
            $url = url('/register') . '/' . $uuid;
            Mail::to($to)->send(new SendTestMail($url));
            return "登録用URLを送信しました。";
        } else {
            preg_match('/(?<=\().*?(?=\))/', $user_name, $num);
            if ($num == []) {
                $num = array(0);
            }
            $num = $num[0] + 1;
            $user_name = preg_replace('/\(.*/', '', $user_name);
            $new_user_name = $user_name . "(" . (string)$num . ")";
            $new_url = url('/register') . "/" . $new_user_name . "/" . $password . "/" . $email;
            return "既に別の方がその名前を使用しています。</br>"
                . '<a href="' . $new_url . '">' . "こちらの名前なら利用できます。" . $new_user_name  . "</a>";
        }
    }

    public function user_login(Request $request) {
        $user_name = $request->input('name');
        $password = $request->input('password');
        if($user_name && $password) {
            $user = User::where('name', $user_name)->first() == null ? null : User::where('name', $user_name)->first();
            if($password == $user->password) {
                $files = $user->files;
                $files_data = serialize($files);
                $user_data = serialize($user);
                session(['owner' => $user->name]);
                return redirect()->action(
                    'UsersController@user_content', ['name' => $user->name]
                );
            } else {
                return back();
            }
        }
    }
}
