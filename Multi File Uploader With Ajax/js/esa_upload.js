/**
 * Created by SURO on 22.08.2015.
 */
var app = app || {};
(function (o) {
    "use strict";

    //Приватные методы
    var ajax, getFormData, setProgress;

    ajax = function (data) {
        var xmlhttp = new XMLHttpRequest(), uploaded;

        xmlhttp.addEventListener('readystatechange',function(){
            if(this.readyState == 4){
                if(this.status == 200){
                    uploaded = JSON.parse(this.responseText);

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

        xmlhttp.upload.addEventListener('progress',function(e){
            var percent;

            if(e.lengthComputable === true){
                percent = Math.round((e.loaded / e.total) * 100);
                setProgress(percent);
            }
        });

        xmlhttp.open('post', o.options.processor);
        xmlhttp.send(data);
    };

    getFormData = function (source) {
        var data = new FormData(),i;

        for(i = 0; i < source.length; i++){
            data.append('file[]',source[i])
        }

        data.append('ajax',true);

        return data;
    };

    setProgress = function(value) {
        if(o.options.progressBar !== undefined){
            o.options.progressBar.style.width = value ? value + '%' : 0;
        }

        if(o.options.progressText !== undefined){
            o.options.progressText.innerText = value ? value + '%' : '';
            o.options.progressText.textContent = value ? value + '%' : '';
        }
    };

    o.uploader = function(options){
        o.options = options;

        if(o.options.files !== undefined){
            ajax(getFormData(o.options.files.files));
        }
    }

}(app))