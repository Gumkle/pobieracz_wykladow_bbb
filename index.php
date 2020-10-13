<?php
    if($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["link"])) {
        $codeName = trim(shell_exec("./venv/bin/python3 ./init.py \"{$_POST['link']}\""));
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
        <input type="submit" value="Pobierz pdf"><br>

        <label for="file">Postęp przetwarzania: </label>
        <progress id="progress_bar" value="32" max="100"></progress>
    </form>

    <script>
        let codename = "<?= $codeName ?>";
        if(codename !== "") {
            console.log(codename);
        }
    </script>
</body>
</html>