<?php
class URL {

    private static $url = null;
    private static $baseUrl = null;

    public static function getBase() {       
        $startUrl   = (substr($_SERVER["DOCUMENT_ROOT"],-1) == "/" ? strlen($_SERVER["DOCUMENT_ROOT"]) : strlen($_SERVER["DOCUMENT_ROOT"]) + 1);
        $excludeUrl = substr($_SERVER["SCRIPT_FILENAME"], $startUrl, -9);

        if ($excludeUrl == "/"):
            self::$baseUrl = $excludeUrl;
        else:
            self::$baseUrl = "/" . $excludeUrl;
        endif;
        
        return self::$baseUrl;
    }

    public static function getURL($id) {
        // Verifica se a lista de URL já foi preenchida
        if (self::$url == null):
            self::getURLList();
        endif;

        // Valida se existe o ID informado e retorna.
        if (isset(self::$url[$id])):
            return self::$url[$id];
        endif;

        // Caso não exista o ID, retorna nulo
        return null;
    }

    private static function getURLList() {
        // Primeiro traz todos as pastas abaixo do index.php
        $startUrl   = strlen($_SERVER["DOCUMENT_ROOT"]) - 1;
        $excludeUrl = substr($_SERVER["SCRIPT_FILENAME"], $startUrl, -10);

        // a variável$request possui toda a string da URL após o domínio.
        $request = $_SERVER['REQUEST_URI'];

        // Agora retira toda as pastas abaixo da pasta raiz
        $request = substr($request, strlen($excludeUrl));

        // Explode a URL para pegar retirar tudo após o ?
        $urlTmp = explode("?", $request);
        $request = $urlTmp[0];

        // Explo a URL para pegar cada uma das partes da URL
        $urlExplodida = explode("/", $request);

        $retorna = array();

        for ($a = 0; $a <= count($urlExplodida); $a ++) {
            if (isset($urlExplodida[$a]) AND $urlExplodida[$a] != "") {
                array_push($retorna, $urlExplodida[$a]);
            }
        }
        self::$url = $retorna;
    }
}
