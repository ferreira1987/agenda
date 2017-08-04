<?php

/**
 * <b>Delete.class</b>
 * Classe responsável por delete genericamento no banco de dados!
 * 
 * @copyright (c) 2016, Adão Ferreira
 */
class Delete extends Conn {

    private $Tabela;
    private $Termos;
    private $Places;
    private $Result;
    private $Error;

    /** @var PDOStatement */
    private $Delete;

    /** @var PDO */
    private $Conn;

    public function ExeDelete($Tabela, $Termos, $ParseString) {
        $this->Tabela = (string) $Tabela;
        $this->Termos = (string) $Termos;
        parse_str($ParseString, $this->Places);

        $this->getSyntax();
        $this->Execute();
    }

    public function getResult() {
        return $this->Result;
    }
    
    public function getError(){
        return $this->Error;
    }    

    public function getRowCount() {
        return $this->Delete->rowCount();
    }

    public function setPlace($ParseString) {
        parse_str($ParseString, $this->Places);
        $this->getSyntax();
        $this->Execute();
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    private function Connect() {
        $this->Conn = parent::getConn();
        $this->Delete = $this->Conn->prepare($this->Delete);
    }

    private function getSyntax() {
        $this->Delete = "DELETE FROM {$this->Tabela} {$this->Termos}";
    }

    private function Execute() {
        $this->Connect();
        try {
            $this->Delete->execute($this->Places);
            $this->Result = true;
        } catch (PDOException $e) {
            $this->Result = null;
            $this->Error = $e->getMessage();
        }
    }

}
