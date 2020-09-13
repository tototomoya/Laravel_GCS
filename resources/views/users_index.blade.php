<?php
echo("<ul>");
$base_url = "http://34.72.82.112:10500/";
foreach ($users as $user){
    echo("<li>" . '<a href="' . $base_url .  $user->name . '">' . $user->name . "さんのページへ行く" . "</a>" . "</li>");
}
?>
{{ $users->links() }}

