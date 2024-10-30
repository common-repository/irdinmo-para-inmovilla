<?php
include  IRDINMO_BASE . 'admin/ird_admutils.php';

$campos = array ();

    array_push ($campos, array ('General', 'Volver', 'volver', ''));
    array_push ($campos, array ('General', 'Piso "en" Localidad', 'textoen', ''));

    array_push ($campos, array ('Listado', 'Operación Venta', 'textoventa', ''));
    array_push ($campos, array ('Listado', 'Operación Alquiler', 'textoalquiler', ''));
    array_push ($campos, array ('Listado', 'Operación Traspaso', 'textotraspaso', ''));
    array_push ($campos, array ('Listado', 'Sin resultados en la búsqueda', 'texto0resultados', ''));
    

    array_push ($campos, array ('Tabla ficha', 'Habitaciones dobles', 'tablahabdobles', ''));
    array_push ($campos, array ('Tabla ficha', 'Habitaciones sencillas', 'tablahabitaciones', ''));
    array_push ($campos, array ('Tabla ficha', 'Baños', 'tablabanyos', ''));
    array_push ($campos, array ('Tabla ficha', 'Metros utiles', 'tablam_uties', ''));
    array_push ($campos, array ('Tabla ficha', 'Calificación energética', 'tablaenergialetra', ''));
    array_push ($campos, array ('Tabla ficha', 'Zona', 'tablazona', ''));
    array_push ($campos, array ('Tabla ficha', 'Estado de conservación', 'tablanbconservacion', ''));
    array_push ($campos, array ('Tabla ficha', 'Metros parcela', 'tablam_parcela', ''));
    array_push ($campos, array ('Tabla ficha', 'Referencia', 'tablaref', ''));


    echo '<H1>General</H1>';

$campos = irdinmo_procesaOpciones ($campos);

irdinmo_pintaCampos ($campos);

?>