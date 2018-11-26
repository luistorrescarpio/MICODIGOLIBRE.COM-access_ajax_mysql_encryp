<?php 
//Script para conexión con base de datos en Mysql
include("db_controller/mysql_script.php");
//Libreria "cryptojs-aes-php"
include("lib/cryptojs-aes-php-master/cryptojs-aes.php");
//Obtenemos todos los parametros enviados desde Ajax en un objecto
$obj = (object)$_REQUEST;

//Clave para encriptar/desencriptar
$myKey = "myKey123";

//Desencriptar el paquete
$obj->password = cryptoJsAesDecrypt($myKey, $obj->password);

//validación de acceso en DB
$r = query("SELECT * FROM account WHERE ac_email='$obj->email' AND ac_password='$obj->password'");

if( count($r)>0 ){

  session_start();
  $_SESSION['user'] = $r[0]; //Pasamos todos los datos del usuario en la variable de sessión user
                             // Esto permitira tener los datos del usuario en cualquier pagina que tenga 
                             // la sessión iniciada (Esto solo lo almacenara hasta que la sessión sea destruida)

  //Reenviamos a la cuenta del Usuario logeado correctamente
  echo json_encode([
  	"success"=>1  //permitido
  	,"message"=>"Acceso Correcto"
  	,"link"=>"my_account.php"
  ]);

}else{
  //Si uno de los campos no coincide, el acceso es denegado y retornado al inicio
  echo json_encode([
  	"success"=>0  //permitido
  	,"message"=>"Acceso Incorrecto"
  ]);
}

// function CryptoJSAesDecrypt($passphrase, $jsonString){

//     $jsondata = json_decode($jsonString, true);
//     try {
//         $salt = hex2bin($jsondata["salt"]);
//         $iv  = hex2bin($jsondata["iv"]);          
//     } catch(Exception $e) { return null; }

//     $ciphertext = base64_decode($jsondata["ciphertext"]);
//     $iterations = 999; //same as js encrypting 

//     $key = hash_pbkdf2("sha512", $passphrase, $salt, $iterations, 64);

//     $decrypted= openssl_decrypt($ciphertext , 'aes-256-cbc', hex2bin($key), OPENSSL_RAW_DATA, $iv);

//     return $decrypted;

// }
?>