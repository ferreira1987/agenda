<?php

/**
 * Description of Auxiliar.class
 * 
 * @copyright (c) 2016, Adão Ferreira
 */
class Auxiliar {

    private static $xml;

    /** Moeda formanto Brasil */
    public static function Moeda($source, $prefix = false) {
        $replace = (strpos($source, ',') ? self::MoedaBase($source) : $source);
        $formato = number_format($replace, 2, ',', '.');
        return $format = ($prefix == true ? "R$ {$formato}" : $formato);
    }

    /** Formato moeda banco de dados */
    public static function MoedaBase($Valor) {
        $value = str_replace(",", ".", str_replace(".", "", $Valor));
        return $value;
    }

    public static function DateBR($source, $horas = false) {
        $strto = strtotime($source);
        $data = ($horas == true ? date("d/m/Y H:i", $strto) : date("d/m/Y", $strto));
        return $data;
    }

    // DATA FORMATO SQL
    public static function DataSQL($source) {
        $ex = explode(" ", $source);
        $data = implode("-", array_reverse(explode("/", $ex[0])));
        
        return $data." ".$ex[1];
    }

    public static function DataExt($Data, $horas = false) {
        $meses = [
            '01' => 'janeiro',
            '02' => 'fevereiro',
            '03' => 'março',
            '04' => 'abril',
            '05' => 'maio',
            '06' => 'junho',
            '07' => 'julho',
            '08' => 'agosto',
            '09' => 'setembro',
            '10' => 'outubro',
            '11' => 'novembro',
            '12' => 'dezembro'
        ];
        $dia = date('d', strtotime($Data));
        $mes = date('m', strtotime($Data));
        $ano = date('Y', strtotime($Data));

        $ext = "{$dia} de {$meses[$mes]} de {$ano}";
        $hrs = date("H:i:s", strtotime($Data));
        return ($horas ? $ext . " às " . $hrs : $ext);
    }

    public static function SearchID($busca, array $Dados) {
        $chave = null;

        foreach ($Dados as $key => $value):
            if (in_array($busca, array_values($value))):
                $chave = $key;
            endif;
        endforeach;

        return $chave;
    }

    public static function my_array_map($func, $array) {
        $result = [];
        foreach ($array as $key => $val):
            $result[$key] = (is_array($val) ? self::my_array_map($func, $val) : $func($val));
        endforeach;

        return $result;
    }

    /**
     * Retorna somente números
     * 
     * @param String $Value
     * @return Números
     */
    public static function OnlyNumber($Value) {
        $string = null;

        if (!empty($Value)):
            $string = preg_replace('/[^0-9]/', "", $Value);
        endif;

        return $string;
    }

    public static function MoedaItau($Value) {
        $moeda = self::Moeda($Value);
        return str_replace(".", "", $moeda);
    }

    /**
     * CONVERTE OBJECT EM ARRAY
     */
    public static function XmlArray($Object) {
        $out = [];
        foreach ((array) $Object as $index => $node) {
            $out[$index] = (is_object($node) ? self::XmlArray($node) : $node);
        }
        return $out;
    }

    // BUSCA ENDERECO E RETORNO ARQUIVO XML
    public static function BuscaEndereco($Cep, $Json = false) {
        $cep = preg_replace("/[^0-9]/", "", $Cep);
        //$url = "http://cep.republicavirtual.com.br/web_cep.php?cep={$cep}&formato=xml";
        $url = "https://viacep.com.br/ws/{$cep}/xml/";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        $result = curl_exec($ch);
        curl_close($ch);

        $xml = simplexml_load_string($result);
        $xml = ($Json == true ? json_encode(self::XmlArray($xml)) : self::XmlArray($xml));

        return $xml;
    }

    // CONSULTA VALOR FRETE CORREIOS
    public static function ConsultaFrete($cep, $peso, $retorno = null) {
        if (!empty($cep)):
            setcookie("CEP", $cep, time() + (7 * 24 * 3600), '/');

            $servico = "41106,40010";
            $comprimento = 30;
            $altura = 15;
            $largura = 25;
            $cepOrigem = preg_replace("/[^0-9]/", "", SITE_ADDR_ZIP);
            $cepDestino = preg_replace("/[^0-9]/", "", $cep);

            $url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?sCepOrigem={$cepOrigem}&sCepDestino={$cepDestino}&nVlPeso={$peso}&nCdFormato=1&nVlComprimento={$comprimento}&nVlAltura={$altura}&nVlLargura={$largura}&sCdMaoPropria=n&sCdAvisoRecebimento=n&nCdServico={$servico}&nVlDiametro=0&StrRetorno=xml";

            self::$xml = simplexml_load_file($url);

            if ($retorno == "html"):
                self::LayoutFrete();
            else:
                return self::$xml;
            endif;
        endif;
    }

    public static function GeraSenha($tamanho, $maiusculas = false, $minusculas = false, $numeros = false, $simbolos = false, $string = NULL) {
        // Caracteres de cada tipo
        $lmin = 'abcdefghijklmnopqrstuvwxyz';
        $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '1234567890';
        $simb = '!@#$%*-';

        // Variáveis internas
        $retorno = '';

        // Agrupamos todos os caracteres que poderão ser utilizados
        $caracteres = ($minusculas ? $lmin : "");
        $caracteres .= ($maiusculas ? $lmai : "");
        $caracteres .= ($numeros ? $num : "");
        $caracteres .= ($simbolos ? $simb : "");
        $caracteres .= ($string ? $string : "");

        // Calculamos o total de caracteres possíveis
        $len = strlen($caracteres);

        for ($n = 1; $n <= $tamanho; $n++):
            // Criamos um número aleatório de 1 até $len para pegar um dos caracteres
            $rand = mt_rand(1, $len);
            // Concatenamos um dos caracteres na variável $retorno
            $retorno .= $caracteres[$rand - 1];
        endfor;

        return $retorno;
    }

    public static function Decimal($Valor) {
        $moeda = NULL;
        $valor = self::OnlyNumber($Valor);
        $digitos = substr($valor, -2);

        if (!empty($valor) && is_numeric($valor)):
            switch (strlen($valor)):
                case 3:
                    $numeros = substr($valor, 0, 1);
                    break;
                case 4:
                    $numeros = substr($valor, 0, 2);
                    break;
                case 5:
                    $numeros = substr($valor, 0, 3);
                    break;
                case 6:
                    $numeros = substr($valor, 0, 4);
                    break;
                case 7:
                    $numeros = substr($valor, 0, 5);
                    break;
                case 8:
                    $numeros = substr($valor, 0, 6);
                    break;
                case 9:
                    $numeros = substr($valor, 0, 7);
                    break;
                case 10:
                    $numeros = substr($valor, 0, 8);
                    break;
                case 11:
                    $numeros = substr($valor, 0, 9);
                    break;
            endswitch;
            
            $moeda = "{$numeros}.{$digitos}";
            
        endif;

        return $moeda;
    }

}
