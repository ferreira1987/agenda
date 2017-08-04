<?php
session_start();
require(__DIR__ . '/../_app/Config.inc.php');
$Event = new Event;
$evento = $Event->getEvento();
$start = Auxiliar::DateBR($evento['date_start'], true);
$end = Auxiliar::DateBR($evento['date_end'], true);
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('[class~="datetimepicker"]').datetimepicker().inputmask("99/99/9999 99:99");
    });
</script> 
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title center-align uppercase">Editar Evento</h4>
</div>
<div class="modal-body form">
    <form action="" method="post" name="alterar_item" class="form horizontal-form updateItem">    
        <div class="form-body"> 
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Resumo</label>                        
                        <input type="text" name="title" value="<?= $evento['title']; ?>" class="form-control" required autocomplete="off" />
                    </div>
                </div>               
            </div> 
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Inicio</label>                        
                        <div class="input-group">                           
                            <input type="text" name="date_start" value="<?= $start; ?>" class="form-control datetimepicker" required autocomplete="off" />
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </div>
                        </div>
                    </div>
                </div>  
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Término</label>                        
                        <div class="input-group">                           
                            <input type="text" name="date_end" value="<?= $end; ?>" class="form-control datetimepicker" required autocomplete="off" />
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Descrição</label>                        
                        <textarea name="descricao" class="form-control" rows="6" ><?= $evento['descricao']; ?></textarea>
                    </div>
                </div>               
            </div>            
        </div>
        <div class="form-actions center-align">
            <input type="hidden" name="id" value="<?= $evento['id']; ?>" />
            <input type="hidden" name="action" value="UpdateEvent" />
            <button type="submit" class="btn green">Aplicar Alterações</button>            
            <button type="button" class="btn red" name="RemoveEvent" data-id="<?= $evento['id']; ?>">Remover Evento</button>      
            <button type="button" class="btn blue" data-dismiss="modal">Cancelar</button>            
        </div>
    </form>
</div>