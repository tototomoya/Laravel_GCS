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
<h3>既にGCSにアップロードしたファイル一覧</h3>
<p>----------------------------------------------------</p>
<?php
$files = $user->files;
echo "<ul>";
$base_url = "http://34.72.82.112:10500/";
foreach($files as $file) {
    echo "<li>". '<a href="' . $base_url . "read/" . $user->name ."/" . $file->path . '">' . $file->path . "</a>" . "</li>";
    echo "<p>" . "最終更新日: " . $file->updated_date . "</p>";
}
echo "</ul>";
?>
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
