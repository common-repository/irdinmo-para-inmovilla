<?php
include IRDINMO_BASE . 'includes/ird_inmovilla.php';

function irdinmo_pintaFiltrosListado ($data) {
    
    $s = '';
    $data['cab'][0] = irdinmo_getAtt ('pagina', '1');
    $act = $data['cab'][0];
    $num = $data['cab'][1];
    $total = $data['cab'][2];
    
    $script = 'function ir (tipo, valor) {';
    $script .= 'document.getElementById ("irdinmo_form_accion").value = tipo;
    if (tipo == "B") {
        document.getElementById ("irdinmo_form_accion").value = "P";
        document.getElementById ("irdinmo_form_id").value = \'\';
        document.getElementById ("irdinmo_form").submit ();
    } else 
    if (tipo == "P") {
        document.getElementById ("irdinmo_form_pagina").value = valor;
        document.getElementById ("irdinmo_form_id").value = \'\';
        document.getElementById ("irdinmo_form").submit ();
    } else if (tipo == "F") {
        document.getElementById ("irdinmo_form_id").value = valor;
        document.getElementById ("irdinmo_form").submit ();
    }';
    $script .= '}';
        
    wp_add_inline_script('irdinmo-slick', $script, 'after');

    $s.= '<form method="post" id="irdinmo_form">';
    /// keyacci 1:Venta,2:Alquiler,3:Traspaso
    $operacion = irdinmo_getAtt ('operacion', '1');
    $oculto = ((irdinmo_getAtt ('id', '') != '') ? 'oculto ' : '');
    $s .= '<div class="irdinmo-operaciones ' . $oculto . '">';
    $s .= '<input type="radio" name="operacion" id="operacion1" value="1" class="irdinmo-botonDisabled oculto" ' . (($operacion == 1)? ' checked ': '').' onchange="ir (\'P\', 1);"><label for="operacion1" class="irdinmo-botonDisabled">' . irdinmo_getOption ('textoventa', 'Venta') . '</label>';
    $s .= '<input type="radio" name="operacion" id="operacion2" value="2" class="irdinmo-botonDisabled oculto" ' . (($operacion == 2)? ' checked ': '').' onchange="ir (\'P\', 1);"><label for="operacion2" class="irdinmo-botonDisabled">' . irdinmo_getOption ('textoalquiler', 'Alquiler') . '</label>';
    $s .= '<input type="radio" name="operacion" id="operacion3" value="3" class="irdinmo-botonDisabled oculto" ' . (($operacion == 3)? ' checked ': '').' onchange="ir (\'P\', 1);"><label for="operacion3" class="irdinmo-botonDisabled">' . irdinmo_getOption ('textotraspaso', 'Traspaso') . '</label>';
    $s .= '</div>';
    
    foreach ($data['filtros'] as $key => $filtro) {
        $data['filtros'][$key][3] = irdinmo_getAtt ($filtro[0], $filtro[3]);
        $s .= ' <input type="hidden" id ="irdinmo_form_' . $filtro [1] . '" name="' . $filtro[0] . '" value="' . $data['filtros'][$key][3] . '">';
    }
    
    $s .= '</form>';
    
    return $s;

}
function irdinmo_pintaPaginacionListado ($data) {
    
    $s = '';
    $act = $data[0];
    $num = irdinmo_getOption ('numpagina', 6);
    $total = $data[2];
    
    if (is_numeric ($total)) {
        for ($i = 1; $i  <= ceil ($total / $num); $i++) {
            $s .= '<div><a href="javascript:ir(\'P\', ' . $i . ');" class="irdinmo-paginacionpag">' . $i . '</a></div>';
        }
        $s = '<div class="irdinmo-paginacion">' . $s . '</div>';
    }
    else {
        $s = '<div class="irdinmo-paginacion"><div class="irdinmo-titulo">' . irdinmo_getOption ('texto0resultados', 'No se han encontrado propiedades.')  . '</div></div>';
    }
    return $s;

}
function irdinmo_pintaPropsListado ($props) {
    $script = '';
    $s = '<div id="listado" class="irdinmo-listado">';
    foreach ($props as &$prop) {
        $s .= '<div id="'. $prop['id'] . '" class="irdinmo-propiedad" >';
            $s .= '<div id="'. $prop['id'] . '_img" class="irdinmo-imagenes">';
                foreach ($prop['fotos'] as $foto) {
                    $s .= '<div class="image"><img data-lazy="' . $foto . '" class="irdinmo-imagen"></div>';
                }
            $s .= '</div>';
            $s .= '<div id="'. $prop['id'] . '_precio" class="irdinmo-precio" onclick="ir(\'F\', ' . $prop['id'] . ')">';
                $s .= '<div class="irdinmo-localidad">' . $prop['tipo'] . irdinmo_getOption ('textoen', ' en ') . $prop['localidad'] .'</div>';
            
                $s .= (($prop['venta'] == '') ? '' : '<div class="irdinmo-venta">' . irdinmo_formatoMoneda ($prop['venta']) .'</div>');
                $s .= (($prop['alquiler'] == '') ? '' :'<div class="irdinmo-alquiler">' . irdinmo_formatoMoneda ($prop['alquiler']) . '<div>');
            $s .= '</div>';
          
            $s .= '<div id="'. $prop['id'] . '_ficha" class="irdinmo-ficha" onclick="ir(\'F\', ' . $prop['id'] . ')">';
            

                $s .= '<div id="'. $prop['id'] . '_detalles" class="irdinmo-detalles">';
                    $s .= (($prop['car1'] == '')? '': '<div class="irdinmo-car1">' . $prop['car1'] .'</div>');
                    $s .= (($prop['car2'] == '')? '': '<div class="irdinmo-car2">' . $prop['car2'] .'</div>');
                    $s .= (($prop['car3'] == '')? '': '<div class="irdinmo-car3">' . $prop['car3'] .'</div>');
                    $s .= (($prop['car4'] == '')? '': '<div class="irdinmo-car4">' . $prop['car4'] .'</div>');
                    $s .= (($prop['car5'] == '')? '': '<div class="irdinmo-car5">' . $prop['car5'] .'</div>');
                $s .= '</div>';

                $s .= '<div id="'. $prop['id'] . '_titulo" class="irdinmo-titulo">';
                    $s .= $prop['titulo'];
                 $s .= '</div>';
                $s .= '<div id="'. $prop['id'] . '_descripcion" class="irdinmo-descripcion">';
                    $s .= irdinmo_reemplaza ($prop['descripcion'], array ('~ ~', '<br>'));
                $s .= '</div>';
                $s .= '<div id="'. $prop['id'] . '_acciones" class="irdinmo-acciones">Acciones';
                $s .= '</div>';
                    
            
            $s .= '</div>';
            
        $s .= '</div>';
        $script .= 'jQuery(\'#' . $prop['id'] . '_img\').slick({
            dots: false,
            infinite: true,
            lazyLoad: \'ondemand\',
            speed: 500,
            fade: true,
            cssEase: \'linear\'
          });';
        
    }
    $s .= '</div>';
    wp_add_inline_script('irdinmo-slick', $script, 'after');
    return $s;
}
function irdinmo_getInlineStyle () {
    $txt = '<style>';
    $txt .= '.irdinmo-listado { display: flex; justify-content: space-between; flex-direction: row; flex-wrap: wrap;}';
    $txt .= '.irdinmo-propiedad {
        margin: 20px;
        background-color: white;    
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-tap-highlight-color: transparent;
        border-radius: 20px;
        overflow:hidden;
        color: #' .  irdinmo_getOption ('colprim', '')  .';
        border-color: #' .  irdinmo_getOption ('colter', '')  .';
        border-width: 1px; 
        border-style:solid;
        box-shadow: 10px 10px #' .  irdinmo_getOption ('colsec', '')  .';
        width: 100%;
        
    }';
    $txt .= '.irdinmo-imagenes{ width:100%; }';

    $txt .= '.irdinmo-imagen {position: absolute; 
        top: 0;
        bottom: 0;
        left: 0;
        right: 0; 
        width: 100%; 
        height: 100%; 
        object-fit: cover; 
        object-position: center;}';
    $txt .= '.image {}';
    $txt .= '.slick-slide {position: relative !important; }';
    $txt .= '.slick-slide:before {content: ""; display: block !important; padding-top: 56% !important}'; /* padding-top: 56% -> formato 16:9; padding-top: 75% formato 4:3*/ 
    
    $txt .= '.irdinmo-ficha {padding: 10px; color: #' .  irdinmo_getOption ('colprim', '')  .';}';
    $txt .= '.irdinmo-titulo {
        width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        font-weight: 500;
        text-align: center;
        padding-top: 0.5em;
        padding-bottom: 0.5em;
        
    }';
    $txt .= '.irdinmo-precio {padding: 10px; display: flex; justify-content: space-between; color: #' .  irdinmo_getOption ('colprim', '')  .'; background-color: #' .  irdinmo_getOption ('colter', '')  .';}';
    $txt .= '.irdinmo-venta { font-weight: bold; white-space: nowrap; }';
    $txt .= '.irdinmo-alquiler { font-weight: bold; white-space: nowrap; }';
    $txt .= '.irdinmo-operaciones {display: flex; justify-content: space-around;}';
    $txt .= '.irdinmo-detalles {display: flex; justify-content: space-between;}';
    $txt .= '.irdinmo-localidad {}';
    $txt .= '.irdinmo-tipo {}';
    $txt .= '.irdinmo-car1 {}';
    $txt .= '.irdinmo-car2 {}';
    $txt .= '.irdinmo-car3 {}';
    $txt .= '.irdinmo-car4 {}';
    $txt .= '.irdinmo-car5 {}';
    
    $txt .= '.irdinmo-car1:before { background: url("' . IRDINMO_BASE_URL . 'public/assets/' . irdinmo_getOption ('icocar1', '') . '.png' . '") left center no-repeat;}';
    $txt .= '.irdinmo-car2:before { background: url("' . IRDINMO_BASE_URL . 'public/assets/' . irdinmo_getOption ('icocar2', '') . '.png' . '") left center no-repeat;}';
    $txt .= '.irdinmo-car3:before { background: url("' . IRDINMO_BASE_URL . 'public/assets/' . irdinmo_getOption ('icocar3', '') . '.png' . '") left center no-repeat;}';
    $txt .= '.irdinmo-car4:before { background: url("' . IRDINMO_BASE_URL . 'public/assets/' . irdinmo_getOption ('icocar4', '') . '.png' . '") left center no-repeat;}';
    $txt .= '.irdinmo-car5:before { background: url("' . IRDINMO_BASE_URL . 'public/assets/' . irdinmo_getOption ('icocar5', '') . '.png' . '") left center no-repeat;}';
    $txt .= '.irdinmo-car1:before, .irdinmo-car2:before, .irdinmo-car3:before, .irdinmo-car4:before, .irdinmo-car5:before { content: \' \';
        vertical-align: middle;
        background-size: 16px auto;
        padding-left: 20px;}';
    

    $txt .= '.irdinmo-descripcion {
        display: block;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;

    }';
    $txt .= '.irdinmo-descripcionficha {
        display: block;
        display: -webkit-box;
        
        text-align: justify;

    }';
    $txt .= '.irdinmo-acciones {text-align:center; display: none}';
    $txt .= '.irdinmo-paginacion {display: flex; justify-content: space-around; }';
    $txt .= '.irdinmo-paginacion-pagina {text-align:center; padding: 10px;}';

    $txt .= '.slick-next {right: 10px !important}';
    $txt .= '.slick-prev {left: 10px !important; z-index: 1;}'; 
    $txt .= '.irdinmo-map {width: 100%; height: 400px; border-style: solid; border-width: 1px; border-color: #' .  irdinmo_getOption ('colprim', '')  .
        ';  margin-top: 20px;}';
    $txt .= '.irdinmo-table {border-style: none; width: 100%; margin-top: 20px;}';
    $txt .= '.irdinmo-tablelbl {border-style: none; min-width: 40%; padding-top: 5px;}';
    $txt .= '.irdinmo-tabletxt {border-style: none; min-width: 40%; padding-top: 5px; font-weight: bold; text-align: right;}';
    $txt .= '.oculto {display:none}';
    $txt .= '.irdinmo-botonDisabled { border-radius: 0.5em; background-color: #' .  irdinmo_getOption ('colsec', '')  .
        ';  color: #' .  irdinmo_getOption ('colprim', '')  . '; border-color: ' . irdinmo_getOption ('colprim', '') .
        '; padding: 0.5em; padding-left: 2em; padding-right: 2em; text-decoration: none}';

    $txt .= '.irdinmo-botonDisabled:checked + label { background-color: #' .  irdinmo_getOption ('colprim', '')  .
        ';  color: #' .  irdinmo_getOption ('colter', '')  . '; }';

    $txt .= '.irdinmo-boton { border-radius: 0.5em; background-color: #' .  irdinmo_getOption ('colprim', '')  .
        ';  color: #' .  irdinmo_getOption ('colter', '')  .
        '; padding: 0.5em; padding-left: 2em; padding-right: 2em; text-decoration: none}';
    
        $txt .= '.irdinmo-boton:hover { color: #' .  irdinmo_getOption ('colsec', '')  . '; }';
        $txt .= ' @media only screen and (min-width: 600px) { .irdinmo-propiedad { max-width: 300px; }}';
        
    $txt .= '</style>';
      
    return $txt;
}

////
function irdinmo_inmovillalst_shortcode() {

    $irdinmo_aivFiltros = array ();
    array_push ($irdinmo_aivFiltros, array ('accion', 'accion', 'txt', 'P'));
    array_push ($irdinmo_aivFiltros, array ('pagina', 'pagina', 'num', '1'));
    array_push ($irdinmo_aivFiltros, array ('id', 'id', 'num', ''));

    $irdinmo_aivListado = array ();
    array_push ($irdinmo_aivListado, array ('id', "ficha:1:cod_ofer", 'txt'));
    array_push ($irdinmo_aivListado, array ('titulo', "descripciones:*:*:titulo", 'txt'));
    array_push ($irdinmo_aivListado, array ('venta', "ficha:1:precioinmo", 'num'));
    array_push ($irdinmo_aivListado, array ('alquiler', "descripciones:*:*:alquiler", 'num'));
    array_push ($irdinmo_aivListado, array ('adicional', "descripciones:*:*:adicional", 'txt'));
    array_push ($irdinmo_aivListado, array ('localidad', "ficha:1:ciudad", 'txt'));
    array_push ($irdinmo_aivListado, array ('tipo', "ficha:1:nbtipo", 'txt'));
    array_push ($irdinmo_aivListado, array ('car1', "ficha:1:habdobles", 'txt'));
    array_push ($irdinmo_aivListado, array ('car2', "ficha:1:habitaciones", 'txt'));
    array_push ($irdinmo_aivListado, array ('car3', "ficha:1:banyos", 'txt'));
    array_push ($irdinmo_aivListado, array ('car4', "ficha:1:m_uties", 'txt'));
    array_push ($irdinmo_aivListado, array ('car5', "ficha:1:energialetra", 'txt'));
    array_push ($irdinmo_aivListado, array ('descripcion', "descripciones:*:*:descrip", 'txt'));
    array_push ($irdinmo_aivListado, array ('fotos', "fotos:*", 'gal'));
    
    
    
    $txt = irdinmo_getInlineStyle ();

     wp_enqueue_style( 'irdinmo-slick', IRDINMO_BASE_URL . 'public/css/slick.1.8.1.css' );
     wp_enqueue_style( 'irdinmo-slick-theme', IRDINMO_BASE_URL .  'public/css/slick-theme.1.8.1.css' );
     wp_enqueue_script( 'irdinmo-slick', IRDINMO_BASE_URL .  'public/js/slick.min.1.8.1.js'  );
   
    /// keyacci 1:Venta,2:Alquiler,3:Traspaso
    $inicio = irdinmo_getAtt ('pagina', '1');
    $accion = irdinmo_getAtt ('accion', 'P');
    $id = irdinmo_getAtt ('id', '');
    
    $keyacci = irdinmo_getAtt ('operacion', '1');
    $numero = irdinmo_getOption ('numpagina', 6);
    $props = array ();
    $props['cab'] = array ($inicio, $numero, 0);
    $props['filtros'] = $irdinmo_aivFiltros;
    $txt .= irdinmo_pintaFiltrosListado ($props);
    
    if ($accion == 'F') {
        $txt .= irdinmo_pintaPropFicha ($id);
    } else {
        $inicio = ((intval ($inicio) -1) * $numero) + 1;
        $props =  irdinmo_getPropListado ($irdinmo_aivListado, $inicio, $numero, $keyacci);

        $txt .= irdinmo_pintaPropsListado ($props['props']);
        $txt .= irdinmo_pintaPaginacionListado ($props['cab']);
    }

    return $txt;
   
    } 

add_shortcode('ird_inmovillalst', 'irdinmo_inmovillalst_shortcode');


function irdinmo_pintaPropFicha ($id) {
    $irdinmo_aivListado = array ();
    array_push ($irdinmo_aivListado, array ('id', "ficha:1:cod_ofer", 'txt'));
    array_push ($irdinmo_aivListado, array ('titulo', "descripciones:*:*:titulo", 'txt'));
    array_push ($irdinmo_aivListado, array ('venta', "ficha:1:precioinmo", 'num'));
    array_push ($irdinmo_aivListado, array ('alquiler', "descripciones:*:*:alquiler", 'num'));
    array_push ($irdinmo_aivListado, array ('adicional', "descripciones:*:*:adicional", 'txt'));
    array_push ($irdinmo_aivListado, array ('localidad', "ficha:1:ciudad", 'txt'));
    array_push ($irdinmo_aivListado, array ('tipo', "ficha:1:nbtipo", 'txt'));

    array_push ($irdinmo_aivListado, array ('descripcion', "descripciones:*:*:descrip", 'txt'));
    array_push ($irdinmo_aivListado, array ('fotos', "fotos:*", 'gal'));
    array_push ($irdinmo_aivListado, array ('latitud', "ficha:1:latitud", 'txt'));
    array_push ($irdinmo_aivListado, array ('longitud', "ficha:1:altitud", 'txt'));

    array_push ($irdinmo_aivListado, array ('habdobles', "ficha:1:habdobles", 'txt'));
    array_push ($irdinmo_aivListado, array ('habitaciones', "ficha:1:habitaciones", 'txt'));
    array_push ($irdinmo_aivListado, array ('banyos', "ficha:1:banyos", 'txt'));
    array_push ($irdinmo_aivListado, array ('m_uties', "ficha:1:m_uties", 'txt'));
    array_push ($irdinmo_aivListado, array ('energialetra', "ficha:1:energialetra", 'txt'));
    array_push ($irdinmo_aivListado, array ('zona', "ficha:1:zona", 'txt'));
    array_push ($irdinmo_aivListado, array ('nbconservacion', "ficha:1:nbconservacion", 'txt'));
    array_push ($irdinmo_aivListado, array ('m_parcela', "ficha:1:m_parcela", 'txt'));
    array_push ($irdinmo_aivListado, array ('ref', "ficha:1:ref", 'txt'));



   
    $prop = irdinmo_getPropFicha ($id, $irdinmo_aivListado);
    $s = '';
    $s .= '<div id="'. $prop['id'] . '_img" class="irdinmo-imagenes">';
        foreach ($prop['fotos'] as $foto) {
            $s .= '<div class="image"><img data-lazy="' . $foto . '" class="irdinmo-imagen"></div>';
        }
    $s .= '</div>';
    $s .= '<div id="'. $prop['id'] . '_precio" class="irdinmo-precio" >';
        $s .= '<div class="irdinmo-localidad">' . $prop['tipo'] . irdinmo_getOption ('textoen', ' en '). $prop['localidad'] .'</div>';
    
        $s .= (($prop['venta'] == '') ? '' : '<div class="irdinmo-venta">' . irdinmo_formatoMoneda ($prop['venta']) .'</div>');
        $s .= (($prop['alquiler'] == '') ? '' :'<div class="irdinmo-alquiler">' . irdinmo_formatoMoneda($prop['alquiler']) . '<div>');
    $s .= '</div>';
  
    $s .= '<div id="'. $prop['id'] . '_ficha" class="irdinmo-ficha" >';
    

       

        $s .= '<div id="'. $prop['id'] . '_titulo" class="irdinmo-titulo">';
            $s .= $prop['titulo'];
         $s .= '</div>';
        $s .= '<div id="'. $prop['id'] . '_descripcion" class="irdinmo-descripcionficha">';
            $s .= irdinmo_reemplaza ($prop['descripcion'], array ('~ ~', '<br>'));
        $s .= '</div>';
        
        $s .= '<div id="'. $prop['id'] . '_acciones" class="irdinmo-acciones">Acciones';
        $s .= '</div>';
 
        if (($prop['latitud'] != '') && ($prop['longitud'] != '')) { // Leaflet Map
           

    
             wp_enqueue_style( 'irdinmo-leaflet', IRDINMO_BASE_URL . 'public/css/leaflet.1.7.1.css' );
             wp_enqueue_script( 'irdinmo-leaflet', IRDINMO_BASE_URL . 'public/js/leaflet.1.7.1.js'  );
            

            $s .= '            <div id="map" class="irdinmo-map" ></div>';

    $script = 'var map = L.map(\'map\').setView([' .  $prop ['latitud'] . ', ' . $prop['longitud'] . '], 16);

	var tiles = L.tileLayer(\'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw\', {
		maxZoom: 18,
		attribution: \'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>\',
		id: \'mapbox/streets-v11\',
		tileSize: 512,
		zoomOffset: -1
	}).addTo(map);

	var marker = L.marker([' .  $prop ['latitud'] . ', ' . $prop['longitud'] . ']).addTo(map);';

wp_add_inline_script('irdinmo-leaflet', $script, 'after');
        }
        $st = '';
        $st .= irdinmo_pintaFilaTabla (irdinmo_getOption ('tabla' . $prop ['ref'], 'Referencia')  , $prop ['ref'] , 'irdinmo-tablelbl', 'irdinmo-tabletxt');
        $st .= irdinmo_pintaFilaTabla (irdinmo_getOption ('tabla' . $prop ['habdobles'], 'Habitaciones dobles')  , $prop ['habdobles'] , 'irdinmo-tablelbl', 'irdinmo-tabletxt');
        $st .= irdinmo_pintaFilaTabla (irdinmo_getOption ('tabla' . $prop ['habitaciones'], 'Habitaciones sencillas')  , $prop ['habitaciones'] , 'irdinmo-tablelbl', 'irdinmo-tabletxt');
        $st .= irdinmo_pintaFilaTabla (irdinmo_getOption ('tabla' . $prop ['banyos'], 'Baños')  , $prop ['banyos'] , 'irdinmo-tablelbl', 'irdinmo-tabletxt');
        $st .= irdinmo_pintaFilaTabla (irdinmo_getOption ('tabla' . $prop ['m_uties'], 'Metros útiles')  , $prop ['m_uties'] , 'irdinmo-tablelbl', 'irdinmo-tabletxt');
        $st .= irdinmo_pintaFilaTabla (irdinmo_getOption ('tabla' . $prop ['m_parcela'], 'Metros de parcela')  , $prop ['m_parcela'] , 'irdinmo-tablelbl', 'irdinmo-tabletxt');
        $st .= irdinmo_pintaFilaTabla (irdinmo_getOption ('tabla' . $prop ['energialetra'], 'Calificación energética')  , $prop ['energialetra'] , 'irdinmo-tablelbl', 'irdinmo-tabletxt');
        $st .= irdinmo_pintaFilaTabla (irdinmo_getOption ('tabla' . $prop ['nbconservacion'], 'Estado de conservación')  , $prop ['nbconservacion'] , 'irdinmo-tablelbl', 'irdinmo-tabletxt');
        
        if ($st != '') {
            $s .= '<table class="irdinmo-table">' . $st . '</table>';
        }
        $s .= '<br>&nbsp;<br><div style="text-align: center"><a href="javascript:ir (\'B\', 0)" class="irdinmo-boton">' . irdinmo_getOption ('volver', 'Volver'). '</a></div>';  

  
    $s .= '</div>';
    

$script = 'jQuery(\'#' . $prop['id'] . '_img\').slick({
    dots: false,
    infinite: true,
    lazyLoad: \'ondemand\',
    speed: 500,
    autoplay: true,
    autoplaySpeed: 2000,
    fade: false,
    cssEase: \'linear\'
  });';
  wp_add_inline_script('irdinmo-slick', $script, 'after');
    return $s;
}
function irdinmo_pintaFilaTabla ($lbl, $val, $lblClass, $valClass) {
    $s = "";
    if ($val != '') {
        $s = '<tr><td class="' . $lblClass . '">' . $lbl . '</td><td class="' . $valClass. '">' . $val . '</td></tr>';
    }
    return $s;
}
function irdinmo_inmovillaficha_shortcode($atts = []) { 
    $id = -1;
    if (isset ($atts['id'])){
        $id = $atts['id'];
    }

    
return irdinmo_pintaPropFicha  ($id);
}
add_shortcode('ird_inmovillaficha', 'irdinmo_inmovillaficha_shortcode');
?>