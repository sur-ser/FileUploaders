<?php
/**
 * Created by SUR-SER.
 * User: SURO
 * Date: 23.08.2015
 * Time: 14:26
 */

require_once "EsaFIleUploader.php";

header('Content-Type: application/json');

$uploader = new EsaFIleUploader();

$uploader->startUploadProcess();
//Выводим результат для javascript обработчика
if(!empty($_POST['ajax'])){
    echo $uploader->getJsonResponce();
}