<?php
/**
 * Created by SUR-SER.
 * User: SURO
 * Date: 23.08.2015
 * Time: 14:11
 */
?>
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

    <script src="<?=$js?>"></script>
    <script>
        <?if($dragAndDrop):?>
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
        <?endif?>
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
                        img,
                        span,
                        i;
                    if(data.failed.length){
                        failed.innerHTML = failed.textContent = '<p>Не загруженные файлы</p>'
                    }

                    uploads.innerText = uploads.textContent = '';

                    for(i = 0; i < data.succeeded.length; i++){

                        anchor = document.createElement('a');
                        anchor.href = data.succeeded[i].file;
                        anchor.title = data.succeeded[i].name;
                        anchor.target = '_blank';
                        anchor.style = 'display: block; margin-bottom: 10px; width: 150px;';
                        img = document.createElement('img');
                        img.src = data.succeeded[i].miniature;
                        img.width = '150';
                        span = document.createElement('span');
                        span.innerText = span.textContent = data.succeeded[i].name;
                        anchor.appendChild(img);
                        anchor.appendChild(span);

                        succeeded.id = 'miniature-container';;
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


