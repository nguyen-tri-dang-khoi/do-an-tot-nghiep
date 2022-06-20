<?php
    $name = "name";
    $game = "game";
    //
    $$name = 'khoi';
    $$game = "aaa";
    $b = "Insert into kh(${$name},${$game}) value('$name','$game')";
    echo $b;
?>