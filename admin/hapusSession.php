<?php

    //fungsi untuk logout 
    session_start();
    session_destroy();
    
    header("Location:../");
    exit();

?>