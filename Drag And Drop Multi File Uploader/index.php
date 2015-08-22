<?php
/**
 * Created by SUR-SER.
 * User: SURO
 * Date: 22.08.2015
 * Time: 17:24
 */
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мультизагрузка файлов</title>
    <link rel="stylesheet" href="css/esa_upload.css">
</head>
<body>
<form action="upload.php" method="post" enctype="multipart/form-data" id="upload" class="upload">
    <fieldset>
        <legend>Загрузка файлов</legend>
        <label>Выберите файлы или перетащите их сюда!</label>
        <input type="file" id="file" name="file[]" required multiple>
        <input type="submit" id="submit" name="submit" value="Загрузить">
    </fieldset>

    <div class="bar">
        <span class="bar-fill" id="pb">
            <span class="bar-fill-text" id="pt"></span>
        </span>
    </div>

    <div id="uploads" class="uploads">
        Загруженные файлы появятся здесь

    </div>

    <script src="js/esa_upload.js"></script>
    <script>
        (function () {
            var dropzone = document.getElementById('upload');

            dropzone.ondragover = function(){
                this.className = 'upload dragover';
                return false;
            };

            dropzone.ondragleave = function(){
                this.className = 'upload';
                return false;
            };
            dropzone.ondrop = function(e){
                e.preventDefault();
                this.className = 'upload';

                startUpload(e.dataTransfer, document.getElementById('pb'), document.getElementById('pt'));

            };
        }());
        /**
         * Обработчик события нажатия на загрузку
         */
        document.getElementById('submit').addEventListener('click',function(e){

            startUpload(document.getElementById('file'),document.getElementById('pb'),document.getElementById('pt'));
            //Тормозим основное событие
            if(e.preventDefault){
                e.preventDefault();
            }else if(e.stopPropagation){
                e.stopPropagation();
            }else{
                return false;
            }

        });

        var startUpload = function(f,pb,pt){
            /**
             * Начинаем работу загрузчика
             */
            esaMultiFileuploader.uploader({
                files: f, //файлы
                progressBar: pb, //строка состояния
                progressText: pt, //текст строки состояня
                processor: 'upload.php', //адресс загрузчика

                //окончание события
                //добавляет информацию на страницу
                finished: function(data){
                    var uploads = document.getElementById('uploads'),
                        succeeded = document.createElement('div'),
                        failed = document.createElement('div'),

                        anchor,
                        span,
                        i;
                    if(data.failed.length){
                        failed.innerHTML = failed.textContent = '<p>Не загруженные файлы</p>'
                    }

                    uploads.innerText = uploads.textContent = '';

                    for(i = 0; i < data.succeeded.length; i++){
                        anchor = document.createElement('a');
                        anchor.href = 'uploads/' + data.succeeded[i].file;
                        anchor.innerText = anchor.textContent = data.succeeded[i].name;
                        anchor.target = '_blank';

                        succeeded.appendChild(anchor);
                    }

                    for(i = 0; i < data.failed.length; i++){
                        span = document.createElement('a');
                        span.innerText = span.textContent = data.failed[i].name;

                        failed.appendChild(span);
                    }

                    uploads.appendChild(succeeded);
                    uploads.appendChild(failed);
                },

                error: function(){
                    console.log('Not working');
                }
            });
        }
    </script>
</form>
</body>
</html>
