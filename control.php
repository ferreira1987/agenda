<?php
session_start();
require_once (__DIR__.'/_app/Config.inc.php');

$action = (!empty(filter_input(INPUT_POST, 'action')) ? filter_input(INPUT_POST, 'action')  : filter_input(INPUT_GET, 'action'));

if(!empty($action)):
    $Event = new Event;

    $Dados = filter_input_array(INPUT_POST);

    switch($action):
        case 'NewEvent':
            $Event->setEvent($Dados);
        break;
        case 'UpdateEvent':
            $Event->updateEvent($Dados);
        break;
        case 'RemoveEvent':
            $Event->deleteEvent($Dados);
        break;
        case 'GetEvent':
            $Event->getEvents();
        break;
        case 'Logar' :
            $Event->Login($Dados);
        break;
    endswitch;
endif;

