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
<?php
use Illuminate\Support\Facades\Storage;
$content = "登録ファイルはありません。";
try{
    $content = Storage::disk('gcs')->get('test/' . $user->name . '.txt');
} catch (Exception $e) {
    report($e);
}
?>
<p>{{$content}}</p>
