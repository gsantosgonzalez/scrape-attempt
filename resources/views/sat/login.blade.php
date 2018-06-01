<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet">
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
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" class="login-form">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 form-group">
                            <label for="login-ciec-rfc">RFC</label>
                            <input type="text" class="form-control" id="login-ciec-rfc" name="rfc" onblur="javascript:this.value = this.value.toUpperCase();">
                        </div>
                        <div class="col-md-6 col-md-offset-3 form-group">
                            <label for="login-ciec-pwd">Contraseña</label>
                            <input type="password" autocomplete="new-password" class="form-control" id="login-ciec-pwd" name="pass">
                        </div>
                        <div class="col-md-6 col-md-offset-3 form-group">
                            <label for="login-ciec-captcha">Captcha</label>
                            <div class="input-group">
                                <span class="input-group-addon" style="padding:0;overflow:hidden;padding:0 10px;background:#fff;">
                                    <img style="height:50px" src="data:image/jpeg;base64,{{ $captcha }}" />
                                </span>
                                <input type="text" class="form-control" autocomplete="new-password" id="login-ciec-captcha" name="captcha" placeholder="Ingrese captcha">
                            </div>
                        </div>
                        <div class="col-md-6 col-md-offset-3 form-group">
                            <label>&nbsp;</label><br>
                            <button type="submit" class="btn btn-success btn-block">Iniciar sesión</button>
                        </div>
                    </div>
                    <input type="hidden" name="sesion" value="{{ $sesion }}" />
                </form>
                <hr>
                <h2>Descarga</h2>
                <div class="tablas-resultados">
                    <div class="overlay"></div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#recibidos" aria-controls="recibidos" role="tab" data-toggle="tab">Recibidos</a></li>
                        <li role="presentation"><a href="#emitidos" aria-controls="emitidos" role="tab" data-toggle="tab">Emitidos</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="recibidos">
                            <form class="form-inline" method="POST" id="recibidos-form">
                                {{ csrf_field() }}
                                <input type="hidden" name="accion" value="buscar-recibidos" />
                                <input type="hidden" name="sesion" class="sesion-ipt" />
                                <div class="form-group">
                                    <label for="dia">Día</label>
                                    <select class="form-control" id="dia" name="dia">
                                    @foreach ($dias as $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="mes">Mes</label>
                                    <select class="form-control" id="mes" name="mes">
                                    @foreach ($meses as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="anio">Año</label>
                                    <select class="form-control" id="anio" name="anio">
                                    @foreach ($anios as $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Buscar</button>
                            </form>
                            <form method="POST" class="descarga-form">
                                {{ csrf_field() }}
                                <input type="hidden" name="accion" value="descargar-recibidos" />
                                <input type="hidden" name="sesion" class="sesion-ipt" />
                                <div style="overflow:auto">
                                    <table class="table table-hover table-condensed tblexportar" id="tabla-recibidos">
                                        <thead>
                                            <tr>
                                                <th class="text-center">XML</th>
                                                <th class="text-center">Acuse</th>
                                                <th>Efecto</th>
                                                <th>Razón Social</th>
                                                <th>RFC</th>
                                                <th>Estado</th>
                                                <th>Folio Fiscal</th>
                                                <th>Emisión</th>
                                                <th>Total</th>
                                                <th>Certificación</th>
                                                <th>Cancelación</th>
                                                <th>PAC</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="text-right">
                                    <button class="btnExportaRecibidos btn btn-success">EXPORTAR EXCEL</button>
                                    <button type="submit" class="btn btn-success">Descargar seleccionados</button>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="emitidos">
                            <form class="form-inline" method="POST" id="emitidos-form">
                                {{ csrf_field() }}
                                <input type="hidden" name="accion" value="buscar-emitidos" />
                                <input type="hidden" name="sesion" class="sesion-ipt" />
                                <div class="form-group">
                                    <label for="dia-e1">Fecha Inicial</label>
                                    <select class="form-control" id="dia-e1" name="dia_i">
                                    @foreach ($dias as $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="mes-e1">Mes</label>
                                    <select class="form-control" id="mes-e1" name="mes_i">
                                    @foreach ($meses as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="anio-e">Año</label>
                                    <select class="form-control" id="anio-e1" name="anio_i">
                                    @foreach ($anios as $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="dia-e2">Fecha Final</label>
                                    <select class="form-control" id="dia-e2" name="dia_f">
                                    @foreach ($dias as $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="mes-e2">Mes</label>
                                    <select class="form-control" id="mes-e2" name="mes_f">
                                    @foreach ($meses as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="anio-e2">Año</label>
                                    <select class="form-control" id="anio-e2" name="anio_f">
                                    @foreach ($anios as $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Buscar</button>
                            </form>
                            <form method="POST" class="descarga-form">
                                {{ csrf_field() }}
                                <input type="hidden" name="accion" value="descargar-emitidos" />
                                <input type="hidden" name="sesion" class="sesion-ipt" />
                                <div style="overflow:auto">
                                    <table class="table table-hover table-condensed tblexportar" id="tabla-emitidos">
                                        <thead>
                                            <tr>
                                                <th class="text-center">XML</th>
                                                <th class="text-center">Acuse</th>
                                                <th>Efecto</th>
                                                <th>Razón Social</th>
                                                <th>RFC</th>
                                                <th>Estado</th>
                                                <th>Folio Fiscal</th>
                                                <th>Emisión</th>
                                                <th>Total</th>
                                                <th>Certificación</th>
                                                <th>PAC</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="text-right">
                                    <button class="btnExportaEmitidos btn btn-success">EXPORTAR EXCEL</button>
                                    <button type="submit" class="btn btn-success">Descargar seleccionados</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/functions.js"></script>
        <script>
            window.onload = function () {
                window.sesionDM = null;
            }

            $(".btnExportaRecibidos").click(function(){
                xport.toCSV('tabla-recibidos');
            });

            $(".btnExportaEmitidos").click(function(){
                xport.toCSV('tabla-emitidos');
            })


            var xport = {
                _fallbacktoCSV: true,

                toXLS: function(tableId, filename) {
                    this._filename = (typeof filename == 'undefined') ? tableId : filename;

                    //var ieVersion = this._getMsieVersion();
                    //Fallback to CSV for IE & Edge
                    if ((this._getMsieVersion() || this._isFirefox()) && this._fallbacktoCSV) {
                        return this.toCSV(tableId);
                    } else if (this._getMsieVersion() || this._isFirefox()) {
                        alert("Not supported browser");
                    }

                    //Other Browser can download xls
                    var htmltable = document.getElementById(tableId);
                    var html = htmltable.outerHTML;

                    this._downloadAnchor("data:application/vnd.ms-excel" + encodeURIComponent(html), 'xls');
                },

                toCSV: function(tableId, filename) {
                    this._filename = (typeof filename === 'undefined') ? tableId : filename;
                    // Generate our CSV string from out HTML Table
                    var csv = this._tableToCSV(document.getElementById(tableId));
                    // Create a CSV Blob
                    var blob = new Blob([csv], { type: "text/csv" });

                    // Determine which approach to take for the download
                    if (navigator.msSaveOrOpenBlob) {
                        // Works for Internet Explorer and Microsoft Edge
                        navigator.msSaveOrOpenBlob(blob, this._filename + ".csv");
                    } else {
                        this._downloadAnchor(URL.createObjectURL(blob), 'csv');
                    }
                },

                _getMsieVersion: function() {
                    var ua = window.navigator.userAgent;

                    var msie = ua.indexOf("MSIE ");
                    if (msie > 0) {
                        // IE 10 or older => return version number
                        return parseInt(ua.substring(msie + 5, ua.indexOf(".", msie)), 10);
                    }

                    var trident = ua.indexOf("Trident/");
                    if (trident > 0) {
                        // IE 11 => return version number
                        var rv = ua.indexOf("rv:");
                        return parseInt(ua.substring(rv + 3, ua.indexOf(".", rv)), 10);
                    }

                    var edge = ua.indexOf("Edge/");
                    if (edge > 0) {
                        // Edge (IE 12+) => return version number
                        return parseInt(ua.substring(edge + 5, ua.indexOf(".", edge)), 10);
                    }

                    // other browser
                    return false;
                },

                _isFirefox: function() {
                    if (navigator.userAgent.indexOf("Firefox") > 0) {
                        return 1;
                    }

                    return 0;
                },

                _downloadAnchor: function(content, ext) {
                    var anchor = document.createElement("a");
                    anchor.style = "display:none !important";
                    anchor.id = "downloadanchor";
                    document.body.appendChild(anchor);

                    // If the [download] attribute is supported, try to use it
                    if ("download" in anchor) {
                        anchor.download = this._filename + "." + ext;
                    }
                    anchor.href = content;
                    anchor.click();
                    anchor.remove();
                },

                _tableToCSV: function(table) {
                    // We'll be co-opting `slice` to create arrays
                    var slice = Array.prototype.slice;

                    return slice
                        .call(table.rows)
                        .map(function(row) {
                            return slice
                                .call(row.cells)
                                .map(function(cell) {
                                    return '"t"'.replace("t", cell.textContent);
                                })
                                .join(",");
                        })
                        .join("\r\n");
                }
            };
        </script>
    </body>
</html>