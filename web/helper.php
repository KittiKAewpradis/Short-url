<?php
function base58_encode($num)
{
    $alphabet = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
    $base_count = strlen($alphabet);
    $encoded = '';
    while ($num >= $base_count) {
        $div = $num / $base_count;
        $remainder = ($num - ($div * $base_count));
        $encoded = $alphabet[$remainder] . $encoded;
        $num = $div;
    }
    if ($num)
        $encoded = $alphabet[$num].$encoded;
    return $encoded;
}

function base58_decode($base58)
{
    $alphabet = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
    $base_count = strlen($alphabet);
    $len = strlen($base58);
    $num = 0;
    for ($i = 0; $i < $len; $i++) {
        $char = $base58[$i];
        $pos = strpos($alphabet, $char);
        if ($pos === false)
            return false; // Invalid character
        $num = bcmul($num, $base_count);
        $num = bcadd($num, $pos);
    }
    return $num;
}


function logout ()
{
    session_destroy();
}

function checklogin ()
{
    if (!isset($_SESSION["username"])) {
        header("Location: welcome");
    }
}


?>