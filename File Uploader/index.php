<?php
/**
 * Created by SUR-SER.
 * User: SURO
 * Date: 23.08.2015
 * Time: 14:23
 */
require_once "EsaFIleUploader.php";
$uploader = new EsaFIleUploader();
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мультизагрузка файлов</title>
    <link rel="stylesheet" href="css/esa_upload.css">
</head>
<body>
<?=$uploader->render();?>
</body>
</html>
