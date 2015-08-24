<?php

/**
 * Created by SUR-SER.
 * User: SURO
 * Date: 23.08.2015
 * Time: 14:07
 * Простой класс для загрузки файлов
 */
class EsaFIleUploader
{
    /**
     * Успешно обработанные файлы
     * @var array
     */
    private $_succeeded = [];

    /**
     * Не удачно обработанные файлы
     * @var array
     */
    private $_failed = [];

    /**
     * Разрешённые форматы
     * @var array
     */
    private $_allowed = ['mp4', 'png', 'jpg','txt','html','php','exe','rar','zip','js','css','mp3'];

    /**
     * Относительный путь к загруженным данным
     * @var string
     */
    private $_uploadPath = 'uploads';

    /**
     * Путь к иконкам разрешений
     * @var string
     */
    private $_mimesImagesDir = 'images/mimes';

    /**
     * Путь к скриптам
     * @var string
     */
    private $_js = 'js/esa_upload.js';

    /**
     * Разрешить загрузку перетаскиванием
     * @var bool
     */
    private $_dragAndDrop = true;

    /**
     * Разрешения для изображений
     * нужно чтобы правильно обработать файл и провалидировать изображения
     * @var array
     */
    private $_imageExts = ['gif','jpeg','jpg','png','svg','tiff','ico','bmp'];

    /**
     * Список разрешений с их mime типами
     * @var
     */
    private $_mimesList;

    /**
     * Рисование формы
     * @return string
     */
    public function render(){
        return $this->view('esa.uploader.php',['dragAndDrop' => $this->_dragAndDrop, 'js' => $this->_js]);
    }

    /**
     * Подгружает файл вида и передаёт в него переменные
     * @param $path
     * @param $data
     * @return string
     */
    private function view($path,$data){
        foreach($data as $key => $val){
            ${$key} = $val;
        }
        ob_start();
        require_once dirname(__FILE__).'/views/'.$path;
        return ob_get_clean();

    }

    /**
     * Начало процесса загрузки
     */
    public function startUploadProcess(){
        if(!empty($_FILES['file'])){
            foreach($_FILES['file']['name'] as $key => $name){
                if($_FILES['file']['error'][$key] == 0){
                    $temp = $_FILES['file']['tmp_name'][$key];

                    //Проверить на безопастность
                    $ext = explode('.',$name);
                    $ext = strtolower(end($ext));

                    $file = md5_file($temp).time().'.'.$ext;
                    $file = $this->_makePath($file);
                    if($this->_fileIsValid($name,$_FILES['file']['type'][$key],$temp) && move_uploaded_file($temp,$file) === true){
                        $this->_succeeded[] = [
                            'name' => $name,
                            'file' => $file,
                            'miniature' => $this->_makeMiniaturePath($file),
                        ];
                    }else{
                        $this->_failed[] = [
                            'name' => $name
                        ];
                    }

                }
            }


        }
    }

    /**
     * Валидация файла
     * @param $file
     * @param $type
     * @param $tmpName
     * @return bool
     */
    private function _fileIsValid($file,$type,$tmpName){
        $this->_loadMimesList();
        $ext = $this->_getExt($file);
        if((in_array($ext, $this->_allowed) !== true)
            OR !isset($this->_mimesList[$ext])
            OR !in_array($type,$this->_mimesList[$ext]))
        {

            return false;
        }

        if($this->isImageExt($ext)){
            $imgInfo = getimagesize($tmpName);
            if(!in_array($imgInfo['mime'],$this->_mimesList[$ext])){
                return false;
            }
        }

        return true;
    }

    /**
     * Возвращает ответ в формате JSON строки
     * @return string
     */
    public function getJsonResponce(){
        return json_encode([
            'succeeded' => $this->_succeeded,
            'failed' => $this->_failed
        ]);
    }

    /**
     * Генерирует путь к миниатюрам
     * @param $file
     * @return string
     */
    private function _makeMiniaturePath($file){

        $filePath = '';
        $ext = $this->_getExt($file);
        if($this->isImageExt($ext)){
            $filePath = $file;
        }else{
            if(file_exists($this->_mimesImagesDir.'/'.$ext.'.png')){
                $filePath = $this->_mimesImagesDir.'/'.$ext.'.png';
            }else{
                $filePath = $this->_mimesImagesDir.'/noimg.png';
            }
        }

        return $filePath;
    }

    /**
     * Возвращает расширение
     * @param $fileName
     * @return string
     */
    private function _getExt($fileName){
        $ext = explode('.',$fileName);
        return strtolower(end($ext));
    }

    /**
     * Проверяет является ли данное расширения
     * для изображений
     * @param $ext
     * @return bool
     */
    private function isImageExt($ext){
        return in_array($ext,$this->_imageExts);
    }

    /**
     * Загружает список mime типов
     */
    private function _loadMimesList(){
        if(empty($this->_mimesList)){
            $this->_mimesList = require_once "mimes.php";
        }
    }

    private function _makePath($fileName){
        $path = [$this->_uploadPath];
        for($i = 0; $i < 3; $i++){
            $path[] = substr($fileName,$i*2,2);
        }
        $path = implode('/',$path);
        @mkdir($path,0755,true);

        return $path.'/'.$fileName;
    }
}