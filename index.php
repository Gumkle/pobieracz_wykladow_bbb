<?php
    if($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["link"])) {
        $pdfName = "python3.8 ./generatePdf.py \"{$_POST['link']}\"";
        header("Location: $pdfName");
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