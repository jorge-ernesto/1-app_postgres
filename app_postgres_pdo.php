<?php

/**
 * Primer contacto con la clase PDO conectandola a PostgreSQL
 * Fichero realizado por mi en preparacion para entrar a Open
 */
class NuclearFactory{
    /******************** Mysql **********************/        
    protected $host = 'localhost';
    protected $port = '5432';
    protected $dbname = 'nuclear';
    protected $username = 'postgres';
    protected $password = 'conejitalinda777';
    protected $options  = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
    /******************** Mongo **********************/

    public function __construct(){   
        try{
            // $this->con = new PDO("pgsql:host=localhost;port=5432;dbname=nuclear;user=postgres;password=conejitalinda777");
            $this->con = new PDO("pgsql:host=$this->host;port=$this->port;dbname=$this->dbname;user=$this->username;password=$this->password;options='--client_encoding=UTF8'");
        }catch (PDOException $e){
			print "¡Error!: " . $e->getMessage() . "<br/>";
			die();
		}
    }
    public function query($sql){
        return $this->con->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function query_by_id($sql){
        return $this->con->query($sql)->fetch();
    }
    public function execute($sql, $data){
		return $this->con->prepare($sql)->execute($data);
	}
	public function execute_insert_last_insert_id($sql, $data){
		$this->con->prepare($sql)->execute($data);
		return $this->con->lastInsertId();
	}
}

/*************** query ***************/
$ncl = new NuclearFactory();
$sql = "SELECT * FROM usuarios ORDER BY id";        
$dataUsuarios = $ncl->query($sql);

foreach ($dataUsuarios as $key=>$value) {
    echo $dataUsuarios[$key]['nombre']."<br>";
}

/*************** query_by_id ***************/
$ncl = new NuclearFactory();
$sql = "SELECT * FROM usuarios LIMIT 1;";        
$dataUsuario = $ncl->query_by_id($sql);

echo "<pre>";
print_r($dataUsuario);
echo "</pre>";

/*************** execute ***************/
$ncl = new NuclearFactory();

$nombre = "Liliana";
$apellidos = "Sturman";

$sql_agregar = "INSERT INTO usuarios (nombre,apellidos) VALUES(?,?);";
$data_agregar = array($nombre,$apellidos);
$ncl->execute($sql_agregar, $data_agregar);

/*************** execute_insert_last_insert_id ***************/
$ncl = new NuclearFactory();

$nombre = "Liliana";
$apellidos = "Sturman";

$sql_agregar = "INSERT INTO usuarios (nombre,apellidos) VALUES(?,?);";
$data_agregar = array($nombre,$apellidos);
$return = $ncl->execute_insert_last_insert_id($sql_agregar, $data_agregar);
echo $return;
