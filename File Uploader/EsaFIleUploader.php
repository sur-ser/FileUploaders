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
    private $_succeeded = [];
    private $_failed = [];
    private $_allowed = ['mp4', 'png', 'jpg','txt','html','php','exe','rar','zip','js','css','mp3'];

    private $_uploadPath = 'uploads';
    private $_mimesImagesDir = 'images/mimes';

    private $_js = 'js/esa_upload.js';
    private $_css = 'css/esa_upload.css';

    private $_dragAndDrop = true;

    private $_imageExts = ['gif','jpeg','jpg','png','svg','tiff','ico','bmp'];

    public function render(){
        return $this->view('esa.uploader.php',['dragAndDrop' => $this->_dragAndDrop, 'js' => $this->_js, 'css' => $this->_css]);
    }

    private function view($path,$data){
        foreach($data as $key => $val){
            ${$key} = $val;
        }
        ob_start();
        require_once dirname(__FILE__).'/views/'.$path;
        return ob_get_clean();

    }

    public function startUploadProcess(){
        if(!empty($_FILES['file'])){
            foreach($_FILES['file']['name'] as $key => $name){
                if($_FILES['file']['error'][$key] == 0){
                    $temp = $_FILES['file']['tmp_name'][$key];

                    //Проверить на безопастность
                    $ext = explode('.',$name);
                    $ext = strtolower(end($ext));

                    $file = md5_file($temp).time().'.'.$ext;

                    if(in_array($ext, $this->_allowed) === true && move_uploaded_file($temp,dirname(__FILE__).'/'.$this->_uploadPath.'/'.$file) === true){
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

    public function getJsonResponce(){
        return json_encode([
            'succeeded' => $this->_succeeded,
            'failed' => $this->_failed
        ]);
    }

    private function _makeMiniaturePath($file){

        $filePath = '';
        $ext = explode('.',$file);
        $ext = strtolower(end($ext));
        if(in_array($ext,$this->_imageExts)){
            $filePath = $this->_uploadPath.'/'.$file;
        }else{
            if(file_exists($this->_mimesImagesDir.'/'.$ext.'.png')){
                $filePath = $this->_mimesImagesDir.'/'.$ext.'.png';
            }else{
                $filePath = $this->_mimesImagesDir.'/noimg.png';
            }
        }

        return $filePath;
    }
}