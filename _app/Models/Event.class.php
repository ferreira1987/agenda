<?php

/**
 * Description of Event
 *
 * @author Adão
 */
class Event {
    
    /* @var Create */
    private $Create;
    /* @var Update */
    private $Update;
    /* @var Read */
    private $Read;
    /* @var Delete */
    private $Delete;
    
    private $Dados;
    
    function __construct() {
        $this->Read = new Read;
        $this->Create = new Create;
        $this->Update = new Update;
        $this->Delete = new Delete;
    }
    
    public function setEvent(array $Dados){
        $this->Dados = Auxiliar::my_array_map("strip_tags", $Dados);
        $date_start = Auxiliar::DataSQL($this->Dados['date_start']);
        $date_end = Auxiliar::DataSQL($this->Dados['date_end']);
        
        $dados = ['usuario' =>  $_SESSION['UserLogin']['ID'], 'title' => $this->Dados['title'], 'date_start' => $date_start, 'date_end' => $date_end, 'descricao' => $this->Dados['descricao']];
        
        $this->Create->ExeCreate('calendario', $dados);
        if($this->Create->getResult()):
            echo json_encode(['erro' => 0, 'message' => 'Evento criado com sucesso!', 'type' => 'success']);
        else:
            echo json_encode(['erro' => 0, 'message' => 'Erro ao criar evento!', 'type' => 'error']);
        endif;
    }
    
    public function updateEvent(array $Dados){
        $this->Dados = Auxiliar::my_array_map("strip_tags", $Dados);
        $date_start = Auxiliar::DataSQL($this->Dados['date_start']);
        $date_end = Auxiliar::DataSQL($this->Dados['date_end']);

        $dados = ['title' => $this->Dados['title'], 'date_start' => $date_start, 'date_end' => $date_end, 'descricao' => $this->Dados['descricao']];
        
        $this->Update->ExeUpdate('calendario', $dados, 'WHERE id = :id', "id={$this->Dados['id']}");
        if($this->Update->getResult()):
            echo json_encode(['erro' => 0, 'message' => 'Evento atualizado com sucesso!', 'type' => 'success']);
        else:
            echo json_encode(['erro' => 0, 'message' => 'Erro ao atualizar evento!', 'type' => 'error']);
        endif;        
    }
    
    public function deleteEvent(array $Dados){
        $this->Delete->ExeDelete('calendario', 'WHERE id = :id', "id={$Dados['id']}");
        
        if($this->Delete->getResult()):
            echo json_encode(['erro' => 0, 'message' => 'Evento deletado com sucesso!', 'type' => 'success']);
        else:
            echo json_encode(['erro' => 0, 'message' => 'Erro ao deletar evento!', 'type' => 'error']);
        endif;        
    }
    
    public function getEvents(){
        $eventos = [];
        
        $this->Read->ExeRead('calendario'); 
        foreach($this->Read->getResult() as $field):
            $eventos[] = ['id' => $field['id'], 'title' => $field['title'], 'start' => $field['date_start'], 'end' => $field['date_end']];
        endforeach;
        
        echo json_encode($eventos);
    }
    
    public function getEvento(){
        $id = filter_input(INPUT_GET, "id");
        $this->Read->ExeRead('calendario', 'WHERE id = :id', "id={$id}"); 
        
        return $this->Read->getResult()[0];
    }
    
    public function Login(array $Dados){
        $this->Dados = Auxiliar::my_array_map("strip_tags", $Dados);
        $senha = md5($this->Dados['password']);     
        
        $this->Read->ExeRead('usuario', 'WHERE EMAIL = :email AND SENHA = :senha', "email={$this->Dados['username']}&senha={$senha}");
        
        if($this->Read->getResult()):
            $_SESSION['UserLogin'] = $this->Read->getResult()[0];
            echo json_encode(['erro' => 0, 'message' => 'Login efetuado com sucesso!', 'type' => 'success', 'url' => 'index.php']);
        else:
            echo json_encode(['erro' => 1, 'message' => 'E-mail ou senha incorreto!', 'type' => 'warning']);
        endif;                
    }
}
