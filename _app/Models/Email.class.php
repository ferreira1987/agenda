<?php
require __DIR__.'/../Library/PHPMailer/class.phpmailer.php';
require __DIR__.'/../Library/PHPMailer/class.smtp.php';

/**
 * Email [ MODEL ]
 * Modelo responável por configurar a PHPMailer, validar os dados e disparar e-mails do sistema!
 * 
 * @copyright (c) year, Robson V. Leite UPINSIDE TECNOLOGIA
 */
class Email {

    /** @var PHPMailer */
    private $Mail;

    /** EMAIL DATA */
    private $Data;

    /** CORPO DO E-MAIL */
    private $Assunto;
    private $Mensagem;

    /** REMETENTE */
    private $RemetenteNome;
    private $RemetenteEmail;

    /** DESTINO */
    private $DestinoNome;
    private $DestinoEmail;
    private $Anexo;
    
    /** CONSTROLE */
    private $Error;
    private $Result;

    function __construct() {
        $this->Mail = new PHPMailer;
        $this->Mail->Host = MAILHOST;
        $this->Mail->Port = MAILPORT;
        $this->Mail->Username = MAILUSER;
        $this->Mail->Password = MAILPASS;
        $this->Mail->CharSet = 'UTF-8';
    }

    /**
     * <b>Enviar E-mail SMTP:</b> Envelope os dados do e-mail em um array atribuitivo para povoar o método.
     * Com isso execute este para ter toda a validação de envio do e-mail feita automaticamente.
     * 
     * <b>REQUER DADOS ESPECÍFICOS:</b> Para enviar o e-mail você deve montar um array atribuitivo com os
     * seguintes índices corretamente povoados:<br><br>
     * <i>
     * &raquo; Assunto<br>
     * &raquo; Mensagem<br>
     * &raquo; RemetenteNome<br>
     * &raquo; RemetenteEmail<br>
     * &raquo; DestinoNome<br>
     * &raquo; DestinoEmail
     * </i>
     */
    public function Enviar(array $Data) {
        $this->Data = $Data;
        $this->Clear();

        $this->setMail();
        $this->Config();
        $this->sendMail();
    }
    
    /**
     * <b>Montar e Enviar:</b> Execute este método para facilitar o envio. Informando os parâmetros solicitados para montar a data! 
     */
    public function EnviarMontando($Assunto, $Mensagem, $RemetenteNome, $RemetenteEmail, $DestinoNome, $DestinoEmail, $Anexo = NULL) {
        $Data['Assunto'] = $Assunto;
        $Data['Mensagem'] = $Mensagem;
        $Data['RemetenteNome'] = $RemetenteNome;
        $Data['RemetenteEmail'] = $RemetenteEmail;
        $Data['DestinoNome'] = $DestinoNome;
        $Data['DestinoEmail'] = $DestinoEmail;
        $Data['Anexo'] = $Anexo;
        $this->Enviar($Data);
    }    

    /**
     * <b>Verificar Envio:</b> Executando um getResult é possível verificar se foi ou não efetuado 
     * o envio do e-mail. Para mensagens execute o getError();
     * @return BOOL $Result = TRUE or FALSE
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * <b>Obter Erro:</b> Retorna um array associativo com o erro e o tipo de erro.
     * @return ARRAY $Error = Array associatico com o erro
     */
    public function getError() {
        return $this->Error;
    }

    /*
     * ***************************************
     * **********  PRIVATE METHODS  **********
     * ***************************************
     */

    //Limpa código e espaços!
    private function Clear() {
        Auxiliar::my_array_map('strip_tags', $this->Data);
        Auxiliar::my_array_map('trim', $this->Data);
    }

    //Recupera e separa os atributos pelo Array Data.
    private function setMail() {
        $this->Assunto  = $this->Data['Assunto'];
        $this->Mensagem = $this->Data['Mensagem'];
        $this->RemetenteNome = $this->Data['RemetenteNome'];
        $this->RemetenteEmail = $this->Data['RemetenteEmail'];
        $this->DestinoNome = $this->Data['DestinoNome'];
        $this->DestinoEmail = $this->Data['DestinoEmail'];
        $this->Anexo = $this->Data['Anexo'];

        $this->Data = null;
        $this->setMsg();
    }

    //Formatar ou Personalizar a Mensagem!
    private function setMsg() {
        $this->Mensagem = $this->Mensagem;
    }

    //Configura o PHPMailer e valida o e-mail!
    private function Config() {
        //SMTP AUTH
        $this->Mail->IsSMTP();
        $this->Mail->SMTPAuth = true;
        $this->Mail->IsHTML();

        //REMETENTE E RETORNO
        $this->Mail->From = MAILUSER;
        $this->Mail->FromName = $this->RemetenteNome;
        $this->Mail->AddReplyTo($this->RemetenteEmail, $this->RemetenteNome);

        //ASSUNTO, MENSAGEM E DESTINO
        $this->Mail->Subject = $this->Assunto;
        $this->Mail->Body = $this->Mensagem;
        $this->Mail->AddAddress($this->DestinoEmail, $this->DestinoNome);
        if(!empty($this->Anexo)):
            $this->Mail->addAttachment($this->Anexo);
        endif;
    }

    //Envia o e-mail!
    private function sendMail() {
        if ($this->Mail->Send()):
            $this->Error = ['erro' => 0, 'msg' =>  'Obrigado por entrar em contato: Recebemos sua mensagem e estaremos respondendo em breve!'];
            $this->Result = true;
        else:
            $this->Error = ["erro" => 1, "msg" => "Erro ao enviar: Entre em contato com o admin. ( {$this->Mail->ErrorInfo} )"];
            $this->Result = false;
        endif;
    }

}
