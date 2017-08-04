<?php

/**
 * <b>Update.class</b>
 * Classe responsável por atualizar banco de dados!
 * 
 * @copyright (c) 2016, Adão Ferreira
 */
class Update extends Conn {

    private $Tabela;
    private $Dados;
    private $Termos;
    private $Places;
    private $Result;
    private $MultiResult;
    private $Error;

    /** @var PDOStatement */
    private $Update;

    /** @var PDO */
    private $Conn;

    public function ExeUpdate($Tabela, array $Dados, $Termos, $ParseString) {
        $this->Tabela = (String) $Tabela;
        $this->Dados = $Dados;
        $this->Termos = (String) $Termos;

        parse_str($ParseString, $this->Places);
        $this->getSyntax();
        $this->Execute();
    }

    public function MultiUpdate($Tabela, array $Dados, $Termos, array $ParseString) {
        $this->Tabela = (String) $Tabela;
        $this->Dados = $Dados;
        $this->Termos = (String) $Termos;
        $this->Places = $ParseString;

        $this->getMultiSyntax();
        $this->MultiExecute();
    }

    public function getResult() {
        return $this->Result;
    }

    public function getMultiResult(){
        return $this->MultiResult;
    }
    
    public function getError() {
        return $this->Error;
    }

    public function getRowCount() {
        return $this->Update->rowCount();
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
        $this->Update = $this->Conn->prepare($this->Update);
    }

    private function getSyntax() {
        foreach ($this->Dados as $Key => $value):
            $Places[] = $Key . ' = :' . $Key;
        endforeach;
        $Places = implode(', ', $Places);

        $this->Update = "UPDATE {$this->Tabela} SET {$Places} {$this->Termos}";
    }

    private function getMultiSyntax() {
        foreach ($this->Dados[0] as $key => $value):
            $Places [] = $key . ' = :' . $key;
        endforeach;
        $Places = implode(', ', $Places);

        for ($x = 0; $x < count($this->Dados); $x++):
            $Dados[] = array_merge($this->Dados[$x], $this->Places[$x]);
        endfor;
        $this->Dados = $Dados;
        
        $this->Update = "UPDATE {$this->Tabela} SET {$Places} {$this->Termos}";   
    }

    private function Execute() {
        $this->Connect();
        try {
            $this->Update->execute(array_merge($this->Dados, $this->Places));
            $this->Result = true;
        } catch (PDOException $e) {
            $this->Result = null;
            $this->Error = $e->getMessage();
        }
    }

    private function MultiExecute() {
        $this->Connect();
        try {
            foreach($this->Dados as $key => $value):
                $this->Update->execute($value);
                $ArrResult[] = true;
            endforeach;
            $this->MultiResult = $ArrResult;
        } catch (PDOException $e) {
            $this->Result = null;
            $this->Error = $e->getMessage();
        }
    }

}
