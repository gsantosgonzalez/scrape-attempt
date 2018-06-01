<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Support\SessionHelper;
use App\Support\AsyncDownload;
use App\Support\SearchEmitted;
use App\Support\SearchRecieved;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\DownloadEmittedRequest;
use App\Http\Requests\DownloadRecievedRequest;

class SATController extends Controller
{
    protected $sessionHelper;
    protected $maxDownloadsAllowed;
    protected $storePath;

    public function __construct(SessionHelper $sessionHelper)
    {
        $this->sessionHelper = $sessionHelper;
        $this->maxDownloadsAllowed = 500;
        $this->storePath = storage_path('tmp');
    }

    public function renderLogin(Request $request)
    {
        $meses = [
            '1' => 'Enero',
            '2' => 'Febrero',
            '3' => 'Marzo',
            '4' => 'Abril',
            '5' => 'Mayo',
            '6' => 'Junio',
            '7' => 'Julio',
            '8' => 'Agosto',
            '9' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        ];

        $dias = range(1, 31);
        $anios = range(date('Y')-1, date('Y'));

        return view('sat.login')->with([
            'captcha' => $this->sessionHelper->getCaptcha(),
            'sesion' => $this->sessionHelper->getSession(),
            'dias' => $dias,
            'meses' => $meses,
            'anios' => $anios,
        ]);
    }

    public function startSession(Request $request)
    {
        return response()->json([
            'status' => true,
            'data' => [
                'captcha' => $this->sessionHelper->getCaptcha(),
                'sesion' => $this->sessionHelper->getSession(),
            ]
        ], 200);
    }

    public function satLogin(LoginRequest $request)
    {
        $this->sessionHelper->restoreSession($request->sesion);

        $rfc = $request->rfc;
        $pass = $request->pass;
        $captcha = $request->captcha;

        if ($this->sessionHelper->iniciarSesionCiecCaptcha($rfc, $pass, $captcha)) {
            return response()->json([
                'status' => true,
                'data' => [
                    'message' => 'Se inició la sesión',
                    'sesion' => $this->sessionHelper->getSession()
                ]
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'data' => [
                   'message' => 'No se inició la sesión'
                ]
            ], 400);
        }
    }

    public function findEmitted(DownloadEmittedRequest $request)
    {
        $this->sessionHelper->restoreSession($request->sesion);

        $data = $request->all();

        $filtros = new SearchEmitted();

        $filtros->establecerFechaInicial(array_get($data, 'anio_i'), array_get($data, 'mes_i'), array_get($data, 'dia_i'));
        $filtros->establecerFechaFinal(array_get($data, 'anio_f'), array_get($data, 'mes_f'), array_get($data, 'dia_f'));

        $xmlInfoArr = $this->sessionHelper->buscar($filtros);

        if ($xmlInfoArr) {
           $items = [];

           foreach ($xmlInfoArr as $xmlInfo) {
                $items[] = (array)$xmlInfo;
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'items' => $items,
                    'sesion' => $this->sessionHelper->getSession()
                ]
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => [
                    'mensaje' => 'No se han encontrado CFDIs'
                ]
            ]);
        }
    }

    public function findRecieved(DownloadRecievedRequest $request)
    {
        $this->sessionHelper->restoreSession($request->sesion);

        $data = $request->all();

        $filtros = new SearchRecieved();

        $filtros->establecerFecha(array_get($data, 'anio'), array_get($data, 'mes'), array_get($data, 'dia'));

        $xmlInfoArr = $this->sessionHelper->buscar($filtros);

        if ($xmlInfoArr) {
           $items = [];

           foreach ($xmlInfoArr as $xmlInfo) {
                $items[] = (array)$xmlInfo;
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'items' => $items,
                    'sesion' => $this->sessionHelper->getSession()
                ]
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => [
                    'mensaje' => 'No se han encontrado CFDIs'
                ]
            ]);
        }
    }

    public function downloadXML(Request $request)
    {
        $this->sessionHelper->restoreSession($request->sesion);

        $descarga = new AsyncDownload($this->maxDownloadsAllowed);

        if (!empty($request->xml)) {
          foreach ($request->xml as $folioFiscal => $url) {
            // $descargaCfdi->guardarXml($url, $ruta, $folioFiscal);
            $descarga->agregarXml($url, $this->storePath, $folioFiscal);
          }
        }

        if (!empty($request->acuse)) {
          foreach ($request->acuse as $folioFiscal => $url) {
            // $descargaCfdi->guardarAcuse($url, $ruta, $folioFiscal);
            $descarga->agregarAcuse($url, $this->storePath, $folioFiscal);
          }
        }

        $descarga->procesar();

        $message = 'Descargados: ' . $descarga->totalDescargados() . '.'
          . ' Errores: ' . $descarga->totalErrores() . '.'
          . ' Duración: ' . $descarga->segundosTranscurridos().' segundos.';

        return response()->json([
            'data' => [
                'mensaje' => $message,
                'sesion' => $this->sessionHelper->getSession()
            ]
        ]);
    }
}