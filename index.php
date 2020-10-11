<?php
    require 'vendor/autoload.php';

    if($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["link"])) {
        $baseUrl = explode("/", $_POST["link"]);
        $baseUrl[sizeof($baseUrl)-1] = "";
        $baseUrl = implode("/", $baseUrl);
        $uniqueDirectoryName = date("Y") . md5(date("m-d:H.i.s") . (string)rand(1000, 2000));
        $currerntDir = scandir(".");
        foreach ($currerntDir as $dirFile) {
            if(substr($dirFile, 0, 4) == date("Y")) {
                $subfiles = scandir($dirFile);
                foreach ($subfiles as $subfile) {
                    unlink($dirFile . DIRECTORY_SEPARATOR . $subfile);
                }
                rmdir($dirFile);
            }
        }
        mkdir($uniqueDirectoryName);
        $i = 1;
        while (true) {
            if(file_put_contents($uniqueDirectoryName . "/" .(string) $i . ".svg", file_get_contents($baseUrl . (string) $i))) {
                $i++;
                continue;
            } else {
                unlink($uniqueDirectoryName . "/" . (string) $i . ".svg");
                break;
            }
        }
        $zipFile = new \PhpZip\ZipFile();
        $zipName = $uniqueDirectoryName . "/wyklady.zip";
        try {
            $zipFile
                ->addDir(__DIR__, $uniqueDirectoryName . '/') // add files from the directory
                ->saveAsFile($zipName) // save the archive to a file
                ->close(); // close archive
        } catch (\PhpZip\Exception\ZipException $e) {
            echo "Pobieranie wykładów nie powiodło się";
        }
        header("Location: $zipName");
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Downloader</title>
</head>
<body>
    <form method="POST" action="index.php">
        <label for="link">Podaj link źródła prezentacji</label>
        <input type="text" name="link" id="link">
        <input type="submit" value="Pobierz zip">
    </form>
</body>
</html>