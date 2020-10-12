<?php
    if($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["link"])) {
        $scriptOutput = trim(shell_exec("python3.8 ./script.py \"{$_POST['link']}\""));
        $errorMessage = null;
        if(substr($scriptOutput, -3) !== "pdf") {
            $errorMessage = $scriptOutput;
        } else {
            $filePath = $scriptOutput;
            $file = fopen($filePath, "r");

            header("Cache-Control: maxage=1");
            header("Pragma: public");
            header("Content-type: application/pdf");
            header("Content-Disposition: inline; filename=wyklady.pdf");
            header("Content-Description: PHP Generated Data");
            header("Content-Transfer-Encoding: binary");
            header('Content-Length:' . filesize($filePath));

            ob_clean();
            flush();
            while (!feof($file)) {
                $buff = fread($file, 1024);
                print $buff;
            }
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Downloader</title>
</head>
<body>
    <span style="color: red;"><?= $errorMessage ?></span>
    <form method="POST" action="index.php">
        <label for="link">Podaj link źródła prezentacji</label>
        <input type="text" name="link" id="link">
        <input type="submit" value="Pobierz pdf">
    </form>
</body>
</html>