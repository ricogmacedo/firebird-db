<?php
namespace FirebirdDB;
use \Exception as Exception;
/**
* Facilitates the use of Firebird with object orientation without using the PDO
* @author   Ricardo Macedo
* @version  1.0.0
*/
class FirebirdDB {

    /*
    * Store Firebird Resource Connection (provided by ibase_connect function)
    * 
    * @return Firebird Resource
    */
    public $conn;

    /*
    * Create a Firebird Connection using the data received in the parameters
    * @param string $ipAddr IP Address
    * @param string $pathFDB Path to .FDB file
    * @param string $nameFDB Name of .FDB file
    * @param string $userDB Firebird Server Username
    * @param string $passwDB Firebird Server Password
    * @param string $charConn Connection Charset
    */
    function __construct($ipAddr, $pathFDB, $nameFDB, $userDB, $passwDB, $charConn) {
        try {
            $strConn = "{$ipAddr}:{$pathFDB}/{$nameFDB}";
            if(!$this->conn = @ibase_connect($strConn, $userDB, $passwDB, $charConn)){
                throw new Exception("Error: " . ibase_errcode() . ' - ' . ibase_errmsg());
            }
        }
        catch( Exception $e ) {
            die( $e->getMessage() );
        }
    }

    /*
    * Create an object with the previously created connection and query sent
    * @param string $query SQL Query
    */
    public function build($query) {
        try {
            if(!$this->conn) {
                throw new Exception("Error: No connection has been established!");
            } else {
                return new QueryBuildDB($this->conn, $query);
            }
        }
        catch( Exception $e ) {
            die( $e->getMessage() );
        }        
    }

}
