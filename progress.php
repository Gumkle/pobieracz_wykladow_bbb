<?php
    if($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET["codename"])) {
        $progressData = shell_exec("./venv/bin/python3 ./progress.py \"{$_GET["codename"]}\"");
        echo $progressData;
    }
