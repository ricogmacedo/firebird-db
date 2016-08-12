<?php
namespace FirebirdDB;
/**
* Classe utilizada para gravar as querys criadas através da FirebirdDB
* @author   Ricardo Macedo
* @version  1.0.0
*/
class QueryBuildDB {

    private $query; // Armazena o objeto de conexão originado pelo ibase_connect
    private $args = 0; // Número de argumentos que foram bindados
    private $results; // Objeto com os Resultados de uma query

    /*
    * Cria a conexão com as informações passadas através dos parametros
    * @param string     $query     Instrução SQL que será executada
    */
    function __construct($query) {
        try {
            if(trim($query) == '' || !isset($query)){
                throw new Exception("Erro: PrepareFirebirdDB::constructor error: Parametro invalido ou em branco");
            } else {
                if(!($this->query = $query)){
                    throw new Exception("Erro: PrepareFirebirdDB::constructor error: Erro para definir o parametro");
                }
            }
        }
        catch( Exception $e ) {
            die( $e->getMessage() );
        }
    }

    /*
    * Verifica se o tipo passado no setParam corresponde ao valor informado
    * @param string     $type     Tipo aceitado pela classe (S para String, I para Integer, N para Double)
    * @param string     $param    Parametro que será testado
    */
    private function typeCheck($type, $param){
        switch ($type) {
            case 'S': return is_string($param); break;
            case 'I': return is_numeric($param); break;
            case 'N': return is_double($param); break;
        }
    }

    /*
    * Retorna o parametro conforme o tipo passado
    * @param multiple   $type     Tipo aceitado pela classe (S para String, I para Integer, N para Double)
    * @param string     $param    Parametro que será "formatado"
    */
    private function setType($type, $param){
        switch ($type) {
            case 'S': return "'{$param}'"; break;
            case 'I': return (int) $param; break;
            case 'N': return (double) $param; break;
        }
    }

    /*
    * Aplica o Parametro Informado na Query
    * @param multiple   $type     Tipo aceitado pela classe (S para String, I para Integer, N para Double)
    * @param string     $param    Parametro que será aplicado
    */
    public function setParam($type, $param) {
        try {
            $type = strtoupper($type);
            if(!$this->typeCheck($type, $param)) {
                throw new Exception("Erro: PrepareFirebirdDB::setParam error: Erro pra definir um tipo ao parametro passado - Tipo: {$type} Parametro: {$param}");
            } else {
                $param = $this->setType($type, $param);
                $posStr = stripos($this->query, '?');
                $this->query = substr_replace($this->query, $param, $posStr, 1);
                $this->args++;
            }
        }
        catch( Exception $e ) {
            die( $e->getMessage() );
        }
    }

    /*
    * Executa a query armazenada no statement retornando o resultado da query
    * @param string     $conn           Objeto da conexão com Firebird
    */
    public function run($conn) {
        try {
            // Se o atributo query estiver vazio, envia um erro
            if(trim($this->query) == '' || !isset($this->query)){
                throw new Exception("Erro: PrepareFirebirdDB::execute error: Sem query definida");
            } else {
                if($bdQuery =  @ibase_query($conn, $this->query)) {
                    // Se query executou sem erros, grava os resultados no atributo do objeto
                    while($resQuery = ibase_fetch_object($bdQuery)){
                        $this->results[] = $resQuery;    
                    }
                } else {
                    // Se teve erro na execução da query, envia um erro
                    throw new Exception("Erro: " . ibase_errcode() . ' - ' . ibase_errmsg());    
                }
            }
        }
        catch( Exception $e ) {
            die( $e->getMessage() );
        }
    }

    /*
    * Retorna o conteudo atual do atributo query
    */
    public function getQuery() {
        return $this->query;
    }

    /*
    * Retorna os resultados da consulta
    */
    public function getResult() {
        return $this->results;
    }
}
