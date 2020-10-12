<?php
    if($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["link"])) {
        $scriptOutput = trim(shell_exec("python3.8 ./script.py \"{$_POST['link']}\""));
        $errorMessage = null;
        if(substr($scriptOutput, -3) !== "pdf") {
            $errorMessage = $scriptOutput;
        } else {
            header("Location: " . $scriptOutput);
            die();
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