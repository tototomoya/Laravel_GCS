@if ($read_only)
    <textarea readonly cols="80" rows="20" ><?php echo $content ?></textarea>
@else
    <?php $url = "http://34.72.82.112:10500/" .  $file->path . "/save"; ?>
    <form method="POST" action="{{ $url }}" >
    {{ csrf_field() }}
    <textarea name="content" cols="80" rows="20" ><?php echo $content ?></textarea>
    <input type="submit" value="保存">
@endif
