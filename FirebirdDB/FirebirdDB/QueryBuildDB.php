<?php
namespace FirebirdDB;
use \Exception as Exception;
/**
* Class used to save queries created by FirebirdDB
* @author   Ricardo Macedo
* @version  1.0.0
*/
class QueryBuildDB {

    private $conn; // Store Firebird Connection received in the object parameter
    private $query; // SQL Statement
    private $args = 0; // Arguments number that were defined in the query
    private $results; // Query result originated of ibase_fetch_object function ibase_query_object

    /*
    * Set class attributes
    * @param string $conn Firebird Connection
    * @param string $query SQL Query
    */
    function __construct($conn, $query) {
        try {
            if(trim($query) == '' || !isset($query)){
                throw new Exception("Erro: PrepareFirebirdDB::constructor error: Invalid or blank parameter");
            } else {
                if(!($this->conn = $conn)){
                    throw new Exception("Erro: PrepareFirebirdDB::constructor error: Error to set parameter CONN");
                }
                if(!($this->query = $query)){
                    throw new Exception("Erro: PrepareFirebirdDB::constructor error: Error to set parameter QUERY");
                }
            }
        }
        catch( Exception $e ) {
            die( $e->getMessage() );
        }
    }

    /*
    * Verifies that the type passed in setParam matches the value entered
    * @param string $type Types (S to String, I to Integer, N to Double)
    * @param string $param Parameter to be tested
    */
    private function typeCheck($type, $param){
        switch ($type) {
            case 'S': return is_string($param); break;
            case 'I': return is_numeric($param); break;
            case 'N': return is_double($param); break;
        }
    }

    /*
    * Returns the value depending on the type informed
    * @param string $type Type accepted by the class (S to String, I to Integer, N to Double)
    * @param string $value Value that will "receive" the type
    */
    private function setType($type, $value){
        switch ($type) {
            case 'S': return "'{$value}'"; break;
            case 'I': return (int) $value; break;
            case 'N': return (double) $value; break;
        }
    }

    /*
    * Apply Parameter informed in Query
    * @param string $type Type accepted by the class (S to String, I to Integer, N to Double)
    * @param string $value Value that will "receive" the type
    */
    public function setParam($type, $value) {
        try {
            $type = strtoupper($type);
            if(!$this->typeCheck($type, $value)) {
                throw new Exception("Erro: PrepareFirebirdDB::setParam error: Error to define a type parameter to the value - Type: {$type} Value: {$value}");
            } else {
                $value = $this->setType($type, $value);
                $posStr = stripos($this->query, '?');
                $this->query = substr_replace($this->query, $value, $posStr, 1);
                $this->args++;
            }
        }
        catch( Exception $e ) {
            die( $e->getMessage() );
        }
    }

    /*
    * Execute a query, saving the result in attribute $results
    */
    public function run() {
        try {
            if(trim($this->query) == '' || !isset($this->query)){
                throw new Exception("Erro: PrepareFirebirdDB::run error: Without defined query");
            } else {
                if($bdQuery =  @ibase_query($this->conn, $this->query)) {
                    while($resQuery = ibase_fetch_object($bdQuery)){
                        $this->results[] = $resQuery;    
                    }
                } else {
                    throw new Exception("Erro: " . ibase_errcode() . ' - ' . ibase_errmsg());    
                }
            }
        }
        catch( Exception $e ) {
            die( $e->getMessage() );
        }
    }

    /*
    * Returns the current content attribute $query
    */
    public function getQuery() {
        return $this->query;
    }

    /*
    * Returns query results
    */
    public function getResult() {
        return $this->results;
    }
    
    /*
    * Returns only one result in the query
    */
    public function getOneResult() {
        return $this->results[0];
    }
}
