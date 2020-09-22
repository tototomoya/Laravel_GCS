<h1>{{ $owner }}</h1>
<p>--------------------------------------------------</p>
<h1>「 {{ $user->name }}」さん</h1>
<p>--------------------------------------------------</p>
<p>あなたのアドレス: {{ $user->email }}</p>
<p>--------------------------------------------------</p>
<h3>GCSへのファイルのアップロード</h3>
<?php $upload_url = url("/upload") . "/" . $user->name; ?>
<form method="POST" action="{{ $upload_url }}" enctype="multipart/form-data">
    {{ csrf_field() }}
     @method('patch')
<input type="file" name="file" class="form-control">
<button type="submit">アップロード</button>
</form>

<p>---------------------------------------------------</p>
<h3>既にGCSにアップロードしたファイル一覧</h3>
<ul>
@foreach($files as $file)
    <?php
    $read_url = url("/read") . "/" . $user->name . "/" . $file->id;
    $delete_url = url("/delete") . "/" . $user->name;
    $dom_form = "form_" . $file->id;
    ?>
    
    <li><a href="{{ $read_url }}"> {{ $file->path }} </a></li>
    <p>最終更新日: {{ $file->updated_date }}</p>
    <form method="post" name="{{ $dom_form }}" action="{{ $delete_url }}">
        {{ csrf_field() }}
        @method('delete')
        <input type="hidden" name="file_id" value={{ $file->id }}>
        <a href={{ "javascript:" . $dom_form . ".submit()" }}>消去</a>
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
        $path_parts = pathinfo($file->path);
        $file_extension = $path_parts["extension"];
        $img_or = in_array($file_extension, ["jpeg", "png", "jpg"]);
        if($img_or) {
            echo "<p>---------------------------------------------------</p>" . $file->path;
            echo '<img src="' . asset('/storage/' . $file->path ) . '">';
        } else {
            $content = Storage::disk('public')->get($file->path);
            echo "<p>---------------------------------------------------</p>" . $file->path;
            echo "<p>" . $content . "</p>";
        }
    }
} catch (Exception $e) {
    report($e);
    echo "<p>" . $content . "</p>";
}
?>

