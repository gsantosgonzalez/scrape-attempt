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
                <form action="{{ route('satLogin') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <label for="rfc">RFC: </label>
                            <input type="text" name="rfc" id="rfc" onblur="javascript:this.value = this.value.toUpperCase();">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <label for="pass">Contraseña: </label>
                            <input type="password" name="pass" id="pass">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <img src="{{ $imgpath }}" alt="">
                            <input type="text" name="captcha" id="captcha">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 pull-right">
                            <input type="submit" value="Entrar" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>