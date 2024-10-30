<?php


function irdinmo_getPropListado ($irdinmo_aivListado, $inicio = 1, $num = 12, $tipoOperacion = 1) {

$tipo = 'paginacion';
$resultado =  irdinmo_getAIV ($tipo,$inicio,$num,"keyacci=$tipoOperacion","fecha desc");
$res = json_decode ($resultado, true);

$res = $res[$tipo];

$pos = irdinmo_getValorArray ($res, "[]:posicion");
$numero = irdinmo_getValorArray ($res, "[]:elementos");
$total = irdinmo_getValorArray ($res, "[]:total");

$propiedades = array ();

if (!is_null ($res)) {
    for  ($i=1;$i<count($res);$i++) {
        $fila = $res[$i];
        $cod = irdinmo_getValorArray ($fila, "cod_ofer");
        
        $prop = irdinmo_getPropFicha ($cod, $irdinmo_aivListado);
       
        array_push ($propiedades, $prop);
    
    
    }

}

$listado = array ();
$listado['cab'] = array ($pos, $numero, $total);
$listado['props'] = $propiedades;

return  $listado;

}



function irdinmo_getPropFicha ($prop, $irdinmo_aivListado) {
    global $wp_locale;
    $tipo = 'ficha';
    $resultado =  irdinmo_getAIV ($tipo,1,1,"cod_ofer=$prop","");
    $res = json_decode ($resultado, true);
    
    $prop = array ();
    foreach ($irdinmo_aivListado as &$item) {
        $prop[$item[0]] = irdinmo_getValorArray ($res, $item[1]);
        if (($item[2] == 'gal') && (!is_array( $prop[$item[0]]) )){
            $prop[$item[0]] = array (  IRDINMO_BASE_URL . 'public/assets/' . irdinmo_getOption ('imgdefecto', '') . '.png');

        }
        

    }
    return $prop;
}





function irdinmo_getAIV ($tipo,$posinicial,$numelementos,$where,$orden){

    return irdinmo_getInmovilla (array ($tipo,$posinicial,$numelementos,$where,$orden));
}

function irdinmo_getInmovilla ($filtros){
    $numagencia = irdinmo_getOption ('agencia', '');
    $addnumagencia = '';
    $password =  irdinmo_getOption ('password', '');
    $idioma = irdinmo_getOption ('idioma', '1');
    $json = 1;

    $texto="$numagencia$addnumagencia;$password;$idioma;";
    $texto .= "lostipos";
    for  ($i=0;$i<count($filtros);$i++) {
        $texto .= ";" .$filtros[$i];
    }

    $texto=rawurlencode($texto);
    $url="https://apiweb.inmovilla.com/apiweb/apiweb.php";

    
    $res  = irdinmo_geturl($url,"param=$texto&json=1");
    return $res;
}

function irdinmo_geturl($url,$campospost)
{

    $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
    $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
    $header[] = "Cache-Control: max-age=0";
    $header[] = "Connection: keep-alive";
    $header[] = "Keep-Alive: 300";
    $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
    $header[] = "Accept-Language: en-us,en;q=0.5";
    $header[] = "Pragma: ";

    if (!isset ($cookie)) {
        $cookie = '';
    }

    if (strlen($campospost)>0) {
        //los datos tienen que ser reales, de no ser asi se desactivara el servicio
        $servidor = "127.0.0.1";
        if ($_SERVER["REMOTE_ADDR"] != '::1') {
                $servidor = $_SERVER["REMOTE_ADDR"];
        }
        if (isset ($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $campospost=$campospost . "&ia=$servidor&ib=" .$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
       
            $campospost=$campospost . "&ia=$servidor&ib=";
        }
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $campospost);
    }
    $body = $campospost;
    $args = array(
        'body'        => $body,
        'timeout'     => '5',
        'redirection' => '5',
        'httpversion' => '1.0',
        'blocking'    => true,
        'headers'     => $header,
        'cookies'     => $cookie,
    );
    $response = wp_remote_post ($url, $args);
    
    return $response['body'];
}

?>