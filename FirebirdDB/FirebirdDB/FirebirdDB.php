<?php
namespace FirebirdDB;
/**
* Classe utilizada para facilitar a utilização do Firebird com orientação a objetos sem utilizar o PDO
* @author   Ricardo Macedo
* @version  1.0.0
*/
class FirebirdDB {

    public $conn; // Armazena o objeto de conexão originado pelo ibase_connect
    public $statement; // Armazena o objeto originada pelo query

    /*
    * Cria a conexão com as informações passadas através dos parametros
    * @param string     $ipHost     Endereço de IP do Servidor Firebird
    * @param string     $addrHost   Caminho até o diretório do arquivo .FDB
    * @param string     $dbName     Nome do arquivo .FDB
    * @param string     $userHost   Nome de Usuário atribuido ao firebird
    * @param string     $passwHost  Senha atribuida ao firebird
    * @param string     $charsHost  Charset utilizado na conexão
    */
    function __construct($ipHost, $addrHost, $dbName, $userHost, $passwHost, $charsHost) {
        try {
            $strConn = "{$ipHost}:{$addrHost}/{$dbName}";
            if(!$this->conn = @ibase_connect($strConn, $userHost, $passwHost, $charsHost)){
                throw new Exception("Erro: " . ibase_errcode() . ' - ' . ibase_errmsg());
            }
        }
        catch( Exception $e ) {
            die( $e->getMessage() );
        }
    }

    /*
    * Cria um objeto com a query que será executada
    * @param string     $query     Instrução SQL que será executada
    */
    public function build($query) {
        return new QueryBuildDB($query);
    }

    /*
    * Cria um objeto com a query que será executada
    * @param string     $query     Instrução SQL que será executada
    */
    public static function sayHello($msg) {
        return $msg;
    }
}
