<?php
// AUTO LOAD DE CLASSES ####################

function __autoload($Class) {
    $cDir = ['Conn','Helps','FrontEnd', 'Admin', 'Models'];

    foreach ($cDir as $dirName):
        if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . "{$Class}.class.php") && !is_dir(__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . "{$Class}.class.php")):
            include_once(__DIR__ . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . "{$Class}.class.php");
        endif;
    endforeach;
}

