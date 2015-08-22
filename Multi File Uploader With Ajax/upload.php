<?php
/**
 * Created by SUR-SER.
 * User: SURO
 * Date: 22.08.2015
 * Time: 17:24
 */
header('Content-Type: application/json');

/**
 * @array
 */
$uploaded = [];
/**
 * Разрешённые форматы
 * @array
 */
$allowed = ['mp4', 'png', 'jpg','txt'];

/**
 * Загруженные файлы
 * @array
 */
$succeeded = [];

/**
 * Не загруженные файлы
 * @array
 */
$failed = [];

if(!empty($_FILES['file'])){
    foreach($_FILES['file']['name'] as $key => $name){
        if($_FILES['file']['error'][$key] == 0){
            $temp = $_FILES['file']['tmp_name'][$key];

            //Проверить на безопастность
            $ext = explode('.',$name);
            $ext = strtolower(end($ext));

            $file = md5_file($temp).time().'.'.$ext;

            if(in_array($ext, $allowed) === true && move_uploaded_file($temp,'uploads/'.$file) === true){
                $succeeded[] = [
                    'name' => $name,
                    'file' => $file
                ];
            }else{
                $failed[] = [
                    'name' => $name
                ];
            }

        }
    }

    //Выводим результат для javascript обработчика
    if(!empty($_POST['ajax'])){
        echo json_encode([
            'succeeded' => $succeeded,
            'failed' => $failed
        ]);
    }
}