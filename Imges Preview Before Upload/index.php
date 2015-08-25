<?php
/**
 * Created by SUR-SER.
 * User: SURO
 * Date: 25.08.2015
 * Time: 16:53
 */
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Превью изображений до отправки</title>
</head>
<body>
<form id="form1" runat="server">
    <input type='file' name="file[]" id="imgInp" multiple/>
    <div id="container">

    </div>
</form>
<script>

        document.getElementById('imgInp').addEventListener('change',function(e){
           readURL(this);
        });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var container = document.getElementById('container');
            for(var i = 0; i < input.files.length; i++){
                    var img = document.createElement('img');
                    img.src = URL.createObjectURL(input.files[i]);
                    container.appendChild(img);

            }
        }
    }

</script>
</body>
</html>
