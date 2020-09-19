<?php $url = url('/save') . "/" . $user_name . "/" . $file_id; ?>
<form method="POST" action="{{ $url }}" >
    {{ csrf_field() }}
    @method('patch')
    <textarea name="content" cols="80" rows="20" >{{ $content }}</textarea>
<input type="submit" value="保存">

