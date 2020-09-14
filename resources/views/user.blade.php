<p>--------------------------------------------------</p>
<h1>「 {{ $user->name }}」さん</h1>
<p>--------------------------------------------------</p>
<p>あなたのアドレス: {{ $user->email }}</p>
<p>--------------------------------------------------</p>
<h3>GCSへのファイルのアップロード</h3>
<?php $url = $user->name . "/upload"; ?>
<form method="POST" action="{{ $url }}" enctype="multipart/form-data">
    {{ csrf_field() }}
<input type="file" id="file" name="file" class="form-control">
<button type="submit">アップロード</button>
</form>
<p>---------------------------------------------------</p>
<h3>既にGCSにアップロードしたファイルの内容</h3>
<p>----------------------------------------------------</p>
<?php
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

$content = "登録ファイルはありません。";
$owner = session('owner') == $user->name ? True : False;
try{
    if($owner) {
        //$content = Storage::disk('gcs')->get('test/' . $user->name . '.txt');
        $content = Storage::disk('local')->get('test/' . $user->name . '.txt');
    }
} catch (Exception $e) {
    report($e);
}
?>
<p>{{ $content }}</p>
