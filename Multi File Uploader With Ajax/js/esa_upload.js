/**
 * Created by SURO on 22.08.2015.
 */
var esaMultiFileuploader = esaMultiFileuploader || {};
(function (o) {
    "use strict";

    //Приватные методы
    var ajax, getFormData, setProgress;

    /**
     * Метод отправляющий ajax запрос к серверу
     * @param data
     */
    ajax = function (data) {
        var xmlhttp = new XMLHttpRequest(), uploaded;

        /**
         * Обработчик события readystatechange
         */
        xmlhttp.addEventListener('readystatechange',function(){
            // Удостоверяемся что данные загруженны
            if(this.readyState == 4){
                //Если код ответа 200 то обрабатывем его иначе выкидываем ошибку
                if(this.status == 200){
                    //Присваемаем возвращённые данные
                    uploaded = JSON.parse(this.responseText);
                    //Передаём данные для обработки
                    if(typeof  o.options.finished === 'function'){
                        o.options.finished(uploaded);
                    }
                }else{
                    if(typeof o.options.error === 'function'){
                        o.options.error();
                    }
                }

            }
        });

        /**
         * Обработчик события progress
         * Увеличивает строку состояния
         */
        xmlhttp.upload.addEventListener('progress',function(e){
            var percent;

            if(e.lengthComputable === true){
                percent = Math.round((e.loaded / e.total) * 100);
                setProgress(percent);
            }
        });

        //Открываем соединение
        xmlhttp.open('post', o.options.processor);
        //Отправляем данные
        xmlhttp.send(data);
    };

    /**
     * Формирует данные в соответствии с формой
     * для отправки запроса
     * @param source
     * @returns {*}
     */
    getFormData = function (source) {
        var data = new FormData(),i;

        for(i = 0; i < source.length; i++){
            data.append('file[]',source[i])
        }

        data.append('ajax',true);

        return data;
    };

    /**
     * Устанавливает прогресс строки состояния
     * @param value
     */
    setProgress = function(value) {
        if(o.options.progressBar !== undefined){
            o.options.progressBar.style.width = value ? value + '%' : 0;
        }

        if(o.options.progressText !== undefined){
            o.options.progressText.innerText = value ? value + '%' : '';
            o.options.progressText.textContent = value ? value + '%' : '';
        }
    };

    /**
     * Загрузчик
     * @param options
     */
    o.uploader = function(options){
        o.options = options;

        if(o.options.files !== undefined){
            ajax(getFormData(o.options.files.files));
        }
    }

}(esaMultiFileuploader));