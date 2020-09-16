<p>--------------------------------------------------</p>
<h1>「 {{ $user->name }}」さん</h1>
<p>--------------------------------------------------</p>
<p>あなたのアドレス: {{ $user->email }}</p>
<p>--------------------------------------------------</p>
<h3>GCSへのファイルのアップロード</h3>
<?php $upload_url = url($user->name . "/upload"); ?>
<form method="POST" action="{{ $upload_url }}" enctype="multipart/form-data">
    {{ csrf_field() }}
     @method('patch')
<input type="file" id="file" name="file" class="form-control">
<button type="submit">アップロード</button>
</form>
<p>---------------------------------------------------</p>
<h3>既にGCSにアップロードしたファイル一覧</h3>

<?php
$read_url = url('/read') . '/';
$delete_url = url('/') . "/" . $user->name . "/delete";
?>
<ul>
@foreach($files as $file)
    <li><a href=" {{ $read_url . $file->path }} "> {{ $file->path }} </a></li>
    <p>最終更新日: {{ $file->updated_date }}</p>
    <form method="post" name="form" action="{{ $delete_url }}">
        {{ csrf_field() }}
        @method('delete')
        <input type="hidden" name="path" value={{ $file->path }}>
        <a href="javascript:form.submit()">消去</a>
    </form>
@endforeach
</ul>

<p>---------------------------------------------------</p>
<h3>既にGCSにアップロードしたファイルの内容</h3>

<?php
use Illuminate\Support\Facades\Storage;

$content = "登録ファイルはありません。";
try{
    foreach($files as $file) {
        $content = Storage::disk('local')->get($file->path);
        echo "<p>---------------------------------------------------</p>" . $file->path;
        echo "<p>" . $content . "</p>";
    }
} catch (Exception $e) {
    report($e);
    echo "<p>" . $content . "</p>";

}
?>
