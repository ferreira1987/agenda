<?php

class Read extends Conn {

    private $Select;
    private $Places;
    private $Result;

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function ExeRead($Tabela, $Termos = null, $ParseString = null) {
        if (!empty($ParseString)):
            $this->Places = $this->proper_parse_str($ParseString);
        endif;

        $this->Select = "SELECT * FROM {$Tabela} {$Termos}";
        $this->Execute();
    }

    public function getResult() {
        return $this->Result;
    }

    public function getRowCount() {
        return $this->Read->rowCount();
    }

    public function FullRead($Query, $ParseString = null) {
        $this->Select = (string) $Query;
        
        if (!empty($ParseString)):
            $this->Places = $this->proper_parse_str($ParseString);
        endif;

        $this->Execute();
    }

    public function setPlaces($ParseString) {
        parse_str($ParseString, $this->Places);
        $this->Execute();
    }

    /**
     * *************************************************
     * *************** METHODS PRIVATES ****************
     * *************************************************
     */
    private function Connect() {
        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($this->Select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
    }

    private function getSyntax() {
        if ($this->Places):
            foreach ($this->Places as $Vinculo => $Valor):
                if ($Vinculo == "limit" || $Vinculo == 'offset'):
                    $Valor = (int) $Valor;
                endif;
                $this->Read->bindValue(":{$Vinculo}", $Valor, (is_int($Valor) ? PDO::PARAM_INT : PDO::PARAM_STR));
            endforeach;
        endif;
    }

    private function Execute() {
        $this->Connect();
        try {
            $this->getSyntax();
            $this->Read->execute();
            $this->Result = $this->Read->fetchAll();
        } catch (PDOException $e) {
            $this->Result = null;
            WSErro("<b>Erro ao ler:</b> {$e->getMessage()}", $e->getCode());
        }
    }

    /**
     * Tranforma um QueryString em array
     * 
     * @param (QueryString) $str
     * @return (Array)
     */
    public static function proper_parse_str($str) {
        $arr = array();
        $pairs = explode('&', $str);

        foreach ($pairs as $i):
            list($name, $value) = explode('=', $i, 2);
            if (isset($arr[$name])):
                if (is_array($arr[$name])):
                    $arr[$name][] = $value;
                else:
                    $arr[$name] = array($arr[$name], $value);
                endif;
            else:
                $arr[$name] = $value;
            endif;
        endforeach;

        return $arr;
    }

}
