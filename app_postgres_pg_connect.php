<?php

class NuclearFactory{
    public function __construct(){
        global $dbconn;        
        $dbconn = pg_connect("host=localhost dbname=nuclear user=postgres password=conejitalinda777") or die('No se ha podido conectar: '.pg_last_error());
        pg_set_client_encoding($dbconn, "utf8");
    }    
    function execute($sql){
        global $dbconn;
        $query = pg_query($dbconn, $sql);        
        return $query;
    }    
    function findById($sql){
        global $dbconn;
        $query = pg_query($dbconn, $sql);        
        $fila = pg_fetch_assoc($query);
        return $fila;
    }
    function executeWithFindByLastId($sql){
        global $dbconn;
        $query = pg_query($sql);       
        $fila = pg_fetch_assoc($query);
        return $fila;
    }
    function clearString($str){
        global $dbconn;
        $str = pg_escape_string($dbconn, trim($str));
        return htmlspecialchars($str);
    }
}

/*************** Traemos imagen codificada en base64 de la base de datos ***************/
$prueba_imagen = false;

if($prueba_imagen){
    $ncl = new NuclearFactory();
    $sql = "SELECT par_valor FROM int_imagenes WHERE par_nombre = 'logo'";        
    $imagen = $ncl->findById($sql);
    $imagen_base64 = $imagen['par_valor'];

    echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Document</title>
            </head>
            <body>
                <img alt="" src="'.$imagen_base64.'">
            </body>
            </html>';
    return;
}

/*************** execute ***************/
$ncl = new NuclearFactory();
$sql = "SELECT * FROM usuarios ORDER BY id";        
$dataUsuarios = $ncl->execute($sql);
$dataUsuarios = pg_fetch_all($dataUsuarios);

foreach ($dataUsuarios as $key=>$value) {
    echo $dataUsuarios[$key]['nombre']."<br>";
}

/*************** findById ***************/
$ncl = new NuclearFactory();
$sql = "SELECT * FROM usuarios WHERE id = 2";        
$dataUsuarios = $ncl->findById($sql);

echo "<pre>";
print_r($dataUsuarios);
echo "</pre>";

/*************** executeWithFindByLastId ***************/
$ncl = new NuclearFactory();
$sql = "INSERT INTO usuarios VALUES(DEFAULT,'Liliana','Sturman') RETURNING id";        
$last_id = $ncl->executeWithFindByLastId($sql);

echo "<pre>";
print_r($last_id['id']);
echo "</pre>";
