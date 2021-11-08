<?php

use GuzzleHttp\Psr7\Response;



class Logger
{
    public static function LogOperacion($request, $response, $next)
    {
        $retorno = $next($request, $response);
        return $retorno;
    }

    public static function ValidarCredenciales($request, $handler)
    {

        if($request->getMethod()=== "GET")
        {
            $respuesta = "No necesita credenciales para GET";
            $response = $handler->handle($request);
            $contenidoApi = (string) $response->getBody();
        }
        else if($request->getMethod()=== "POST")
        {
            $respuesta = "Verifico credenciales";

            $arrayParams = $request->getParsedBody();
            $perfil = $arrayParams['perfil'];
            $nombre = $arrayParams['nombre'];

            if($perfil == 'administrador')
            {
                $respuesta = $respuesta .  "<br> Bienvenido " . $nombre;
                $response = $handler->handle($request);
                $contenidoApi = (string) $response->getBody();

            }
            else
            {
                $contenidoApi = "No tienes habilitado el ingreso";
            }
    
        }
        
        //nueva respuesta
        $response = new Response();
        $vuelvo = "Vuelvo al validador de credenciales";
        $response->getBody()->write("{$respuesta} <br> {$contenidoApi} <br> {$vuelvo}");
        return $response;
    }

}