<?php
include IRDINMO_BASE . 'admin/ird_admutils.php';
$campos = array ();


array_push ($campos, array ('Conexión', 'Código de Agencia', 'agencia', ''));
array_push ($campos, array ('Conexión', 'Contraseña', 'password', ''));
array_push ($campos, array ('Conexión', 'Idioma', 'idioma', ''));
array_push ($campos, array ('Paginación', 'Número de propiedades', 'numpagina', ''));

array_push ($campos, array ('Colores', 'Primario', 'colprim', ''));
array_push ($campos, array ('Colores', 'Secundario', 'colsec', ''));
array_push ($campos, array ('Colores', 'Contraste', 'colter', ''));


array_push ($campos, array ('Características', 'Característica 1', 'nomcar1', ''));
array_push ($campos, array ('Características', 'Característica 2', 'nomcar2', ''));
array_push ($campos, array ('Características', 'Característica 3', 'nomcar3', ''));
array_push ($campos, array ('Características', 'Característica 4', 'nomcar4', ''));
array_push ($campos, array ('Características', 'Característica 5', 'nomcar5', ''));

array_push ($campos, array ('Imágenes', 'Imagen defecto', 'imgdefecto', ''));
array_push ($campos, array ('Imágenes', 'Icono venta', 'icoventa', ''));
array_push ($campos, array ('Imágenes', 'Icono alquiler', 'icoalquiler', ''));
array_push ($campos, array ('Imágenes', 'Icono car1', 'icocar1', ''));
array_push ($campos, array ('Imágenes', 'Icono car2', 'icocar2', ''));
array_push ($campos, array ('Imágenes', 'Icono car3', 'icocar3', ''));
array_push ($campos, array ('Imágenes', 'Icono car4', 'icocar4', ''));
array_push ($campos, array ('Imágenes', 'Icono car5', 'icocar5', ''));

echo '<H1>API Inmovilla</H1>';

$campos = irdinmo_procesaOpciones ($campos);

irdinmo_pintaCampos ($campos);



?>




