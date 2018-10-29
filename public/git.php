<?php
$post = file_get_contents("php://input");
$file = 'git.log';
file_put_contents($file, $post, FILE_APPEND);
$post = json_decode($post, true);
if ($post['password'] == 'wwwwc87com') {
    $line_01 = system('git fetch --all');
    $line_02 = system('git reset --hard origin/master');
    file_put_contents($file, $line_01, FILE_APPEND);
    file_put_contents($file, $line_02, FILE_APPEND);
}
