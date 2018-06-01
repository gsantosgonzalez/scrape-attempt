<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="row">
                    <div id="contenido"></div>
                </div>
            </div>
        </div>
        <script>
            var xhr = new XMLHttpRequest();

            xhr.open("GET", "https://login.siat.sat.gob.mx/nidp/app/login?id=ptsc-ciec&sid=0&option=credential&sid=0", true);
            xhr.onreadystatechange = function() {
            console.log(xhr.readyState);

                if (xhr.readyState == 4) {
                    // innerText does not let the attacker inject HTML elements.
                    document.getElementById("contenido").innerText = xhr.responseText;
                }
            }
            xhr.send();
        </script>
    </body>
</html>
