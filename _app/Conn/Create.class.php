<?php

class Create extends Conn {

    private $Tabela;
    private $Dados;
    private $Result;
    private $MultiResult;
    private $Error;
    private $RowsAffect;
    
    /** @var PDOStatement */
    private $Create;

    /** @var PDO */
    private $Conn;

    public function ExeCreate($Tabela, array $Dados) {
        $this->Tabela = (String) $Tabela;
        $this->Dados = $Dados;

        $this->getSyntax();
        $this->Execute();
    }

    public function MultiCreate($Tabela, array $Dados) {
        $this->Tabela = (string) $Tabela;
        $this->Dados = $Dados;

        $this->getMultiSyntax();
        $this->MultiExecute();
    }

    public function getResult() {
        return $this->Result;
    }
    
    /** Retorna um Array */
    public function getMultiResult(){
        return $this->MultiResult;
    }
    
    public function getError(){
        return $this->Error;
    }
    
    public function getRowAffect(){
        return $this->RowsAffect;
    }

    /**
     * *************************************************
     * *************** METHODS PRIVATES ****************
     * *************************************************
     */
    private function Connect() {
        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($this->Create);
    }

    private function getSyntax() {
        $Fields = implode(', ', array_keys($this->Dados));
        $Places = ':' . implode(', :', array_keys($this->Dados));
        $this->Create = "INSERT INTO {$this->Tabela} ({$Fields}) VALUES ({$Places})";
    }

    private function getMultiSyntax() {
        $Fields = implode(', ', array_keys($this->Dados[0]));
        $Places = ':' . implode(", :", array_keys($this->Dados[0]));      
        $this->Create = "INSERT INTO {$this->Tabela} ({$Fields}) VALUES ({$Places})";
    }

    private function Execute() {
        $this->Connect();
        try {
            $this->Create->execute($this->Dados);
            $this->Result = $this->Conn->lastInsertId();
            $this->RowsAffect = $this->Create->rowCount();
        } catch (PDOException $e) {
            $this->Result = null;
            $this->Error = $e->getMessage();
        }
    }

    private function MultiExecute() {
        $this->Connect();
        $ArrResult = [];
        
        try {
            foreach ($this->Dados as $key => $value):
                $this->Create->execute($value);
                array_push($ArrResult, $this->Conn->lastInsertId());
            endforeach;
            $this->MultiResult = $ArrResult;
        } catch (PDOException $e) {
            $this->MultiResult = null;
            $this->Error = $e->getMessage();
        }
    }

}
