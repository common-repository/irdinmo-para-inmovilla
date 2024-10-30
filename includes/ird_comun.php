<?php


global $wpdb;
$irdinmo_prefix = 'irdinmo';

function irdinmo_getOption ($campo, $defecto) {
    global $irdinmo_prefix;
    return get_option( $irdinmo_prefix . '_' . $campo, $defecto);
}
function irdinmo_addOption ($campo, $defecto) {
    global $irdinmo_prefix;
	echo '<br>Guardando valor para el campo ' .  esc_html ($irdinmo_prefix . '_' . $campo . ": " . $defecto) ;
    update_option( $irdinmo_prefix . '_' . $campo, $defecto);
}

function irdinmo_reemplazaTxt ($txt, $sustituir, $tag, $tagfin) {
	$inicio = stripos ($txt, $tag);
	
	while ($inicio) {
	
		$fin = stripos ($txt, $tagfin, $inicio);
		if ($fin) {
			$txt = substr ($txt, 0, $inicio) . $sustituir  .substr ($txt, $fin + strlen ($tagfin));
		}
		$inicio = stripos ($txt, $tag);
		
	}
	return $txt;
}

function irdinmo_reemplaza ($txt, $multi) {

	foreach ($multi as &$item) {
		$txt = str_replace ($item[0], $item[1], $txt);
	}
	return $txt;
}

function irdimmo_cortaTxt ($txt, $largo) {
	$s = $txt;
	
	$largoInc = $largo;
    if (strlen ($txt) > $largo) {
		$largo = $largo + 50;
		$h = stripos($s, '</h', $largoInc);
		if ($h) {
			if ($h < $largo){
				$largo = $h + 4;
		
			}
		}
		$p = stripos($s, '</p', $largoInc);
		if ($p) {
			if ($p < $largo){
				$largo = $p + 3;
			}
		}
		$H = stripos($s, '</h', $largoInc);
		if ($H) {
			if ($H < $largo){
				$largo = $H + 4;
			}
		}
		$P = stripos($s, '</P', $largoInc);
		if ($P) {
			if ($P < $largo){
				$largo = $P + 3;
			}
		}
		
		$s = substr ($txt, 0, $largo) ;
		if ($largo == ($largoInc + 50)) {
			$s .= '...';
		}
    }
    return $s;
}



function irdinmo_getAtt ($nombre, $defecto) {
	
	return (isset ($_POST [$nombre])) ? sanitize_text_field ($_POST [$nombre]) : (isset ($_GET [$nombre]) ? sanitize_text_field ($_GET [$nombre]) : $defecto);
}

function irdinmo_getAttSer ($ser, $clave) {
	$s = '';
    $aux = unserialize ($ser);
    foreach ($aux as &$item) {
    	if ($item[0] == $clave){
        	$s = $item[1];
        }
    }
    return $s;
}


function irdinmo_formatoMoneda ($numero, $decimales = 2) {	
    return number_format ( floatval ($numero), $decimales , "," , "." ) . ' €';
}


	/*
	 * traza 
	 * Deja registrada una traza en el registro particular
	 * @return void
	 */ 
    function irdinmo_traza($tipo, $cadena) {
		$fichHoy = "irdtrc_".date("Y-m-d").".txt";
		$dir = get_home_path() ."logs/";
        $nombre_archivo = $dir . $fichHoy;
        if (file_exists($nombre_archivo)) {
			$arch = fopen($nombre_archivo, "a+");
       
		}
		else {
			$arch = fopen($nombre_archivo, "w");
		}
         fwrite($arch, "[".date("Y-m-d H:i:s"). " - " .$tipo ." ] ".$cadena."\n");
        fclose($arch);
		

		if (is_dir($dir)){
			if ($dh = opendir($dir)){
				while (($file = readdir($dh)) !== false){
					if (($file != $fichHoy) && (strpos($file, '.txt') > 0)){	
						$zip = new ZipArchive();
						$zip->open($dir . substr($file, 0, strlen ($file)- 4)  . '.zip', ZipArchive::CREATE);
						$zip->addFile($dir . $file, $file);
						$zip->close();

						unlink($dir . $file);
					}
				}
				closedir($dh);
			}
		} 

    }

	function irdinmo_dump ($obj) {
		$s = 
		ob_start();
		var_dump($obj);
		$s = ob_get_clean();
		return $s;
	}

function irdinmo_getValorArray ($item, $clave) {
    $s = '';
    $cod = explode (':', $clave);
  
    
    if (($cod[0] == '*') && is_array ($item)) {
         
       
        reset($item);
        $aux = current($item);
		if (sizeof($cod) > 1) {
        	$s = irdinmo_getValorArray ($aux, implode (":", array_slice ($cod, 1)));
		}
		else {
			$s = $aux;
		}
    }
    else if (($cod[0] == '[]') && is_array ($item)){
        if (sizeof($cod) > 1) {
            $s = irdinmo_getValorArray ($item[0], implode (":", array_slice ($cod, 1)));
        }
        else {
            $s = $item[0];
        }
    } 
    else
    if (($item != null) && (array_key_exists($cod[0],$item))) {
        if (sizeof($cod) > 1) {
            $s = irdinmo_getValorArray ($item[$cod[0]], implode (":", array_slice ($cod, 1)));
        }
        else {
            $s = $item[$cod[0]];
        }

    }
   
    return $s;

}
function irdinmo_getPostById ($formulario, $campo, $valor) {
     $posts = new WP_Query( array( 'post_type' => $formulario, 'meta_key' => $campo, 'meta_value' => $valor, 'post_status' => 'publish' ) ); //var_dump ($posts);
    $ret = null;

    if ($posts->have_posts()) {
        $ret = $posts->posts[0];
    }
    wp_reset_postdata();

 
    return $ret;
}






?>