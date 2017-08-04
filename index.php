<?php
session_start();
ob_start();

if (empty($_SESSION['UserLogin'])):
    header('Location: login.php');
    exit();
endif;

require_once (__DIR__ . '/_app/Config.inc.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Adão Ferreira" name="author" />  
        <title>Agenda de Eventos</title>
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" type="text/css" rel="stylesheet" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet" />
        <link href="assets/global/plugins/fullcalendar-3.4.0/fullcalendar.min.css" type="text/css" rel="stylesheet" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" type="text/css" rel="stylesheet" />
        <link href="assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" />
        <link href="assets/global/plugins/sweetalert2/sweetalert2.min.css" type="text/css" rel="stylesheet" />
        <link href="<?= URL::getBase(); ?>assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" type="text/css" rel="stylesheet" />
        <link href="<?= URL::getBase(); ?>assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css" type="text/css" rel="stylesheet" />        

        <link href="assets/global/css/components.min.css" type="text/css" rel="stylesheet" />
        <link href="assets/global/css/plugins.min.css" type="text/css" rel="stylesheet" />
        <link href="assets/layouts/layout3/css/layout.min.css" type="text/css" rel="stylesheet" />
        <link href="assets/layouts/layout3/css/themes/default.min.css" type="text/css" rel="stylesheet" />
        <link href="assets/layouts/custom.min.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript">
            function getbase() {
                return '<?= URL::getBase(); ?>';
            }
        </script>
    </head>
    <body class="page-container-bg-solid page-boxed">
        <div class="page-header">
            <!-- BEGIN HEADER TOP -->
            <div class="page-header-top">
                <div class="container">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                        <a href="<?= URL::getBase(); ?>">
                            <img src="assets/images/logo.png" alt="logo" class="logo-default">
                        </a>
                    </div>
                    <!-- END LOGO -->
                    <div class="top-menu">
                        <ul class="nav navbar-nav pull-right">
                            <!-- BEGIN USER LOGIN DROPDOWN -->
                            <li class="dropdown dropdown-user dropdown-dark">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <img alt="" class="img-circle" src="assets/layouts/layout3/img/avatar.png">
                                    <span class="username username-hide-mobile"><?= $_SESSION['UserLogin']['NOME']; ?></span>
                                </a>
                                <!--                                <ul class="dropdown-menu dropdown-menu-default">
                                                                    <li>
                                                                        <a href="page_user_profile_1.html">
                                                                            <i class="icon-user"></i> My Profile </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="app_calendar.html">
                                                                            <i class="icon-calendar"></i> My Calendar </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="app_inbox.html">
                                                                            <i class="icon-envelope-open"></i> My Inbox
                                                                            <span class="badge badge-danger"> 3 </span>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="app_todo_2.html">
                                                                            <i class="icon-rocket"></i> My Tasks
                                                                            <span class="badge badge-success"> 7 </span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="divider"> </li>
                                                                    <li>
                                                                        <a href="page_user_lock_1.html">
                                                                            <i class="icon-lock"></i> Lock Screen </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="page_user_login_1.html">
                                                                            <i class="icon-key"></i> Log Out </a>
                                                                    </li>
                                                                </ul>-->
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->
                            <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                            <li>
                                <a href="<?= URL::getBase(); ?>logoff.php">
                                    <i class="icon-login"></i>
                                </a>
                            </li>
                            <!-- END QUICK SIDEBAR TOGGLER -->
                        </ul>
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
            </div>
            <!-- END HEADER TOP -->
        </div>        

        <div class="page-container">
            <div class="page-content-wrapper">
                <div class="page-content">
                    <div class="container">
                        <div class="portlet light portlet-fit bordered calendar">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-calendar font-green"></i>
                                    <span class="caption-subject font-green sbold uppercase">Agenda</span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <h3 class="event-form-title margin-bottom-20">Adicionar Evento</h3>
                                        <form action="" method="post" name="form-events" class="form">
                                            <div class="form-group">
                                                <label class="control-label">Resumo</label>                                      
                                                <input type="text" name="title" class="form-control" required autocomplete="off" />
                                            </div>           
                                            <div class="form-group">
                                                <label class="control-label">Ínicio</label>
                                                <div class="input-group date">
                                                    <input type="text" name="date_start" class="form-control datetimepicker" required autocomplete="off" />
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Término</label>
                                                <div class="input-group date">
                                                    <input type="text" name="date_end" class="form-control datetimepicker" required autocomplete="off" />
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Descrição</label>                                      
                                                <textarea class="form-control" name="descricao" rows="5"></textarea>
                                            </div> 
                                            <div class="form-group">
                                                <input type="hidden" name="action" value="NewEvent" />
                                                <button type="submit" class="btn green"><i class="fa fa-save"></i>&nbsp; Salvar</button>
                                            </div>                                 
                                        </form>
                                    </div>
                                    <div class="col-md-9 col-sm-12">
                                        <div id="calendar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="ajax-modal" class="modal fade" tabindex="-1"> </div>

        <div class="page-footer">
            <div class="container"> 2017 © Calendário de Eventos. Desenvolvido por: 
                <a href="mailto:ramos.adao@outlook.com" target="_blank">Adão Ferreira</a>
            </div>
        </div>        

        <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/fullcalendar-3.4.0/fullcalendar.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/fullcalendar-3.4.0/locale/pt-br.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.pt-BR.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>        
        <script src="assets/global/plugins/sweetalert2/sweetalert2.min.js" type="text/javascript"></script>    
        <script src="<?= URL::getBase(); ?>assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript"></script>
        <script src="<?= URL::getBase(); ?>assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript"></script>        
        <script src="assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>        

        <script src="assets/global/scripts/app.js" type="text/javascript"></script>
        <script src="assets/apps/scripts/calendar.js" type="text/javascript"></script>
        <script src="assets/javascripts/custom.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('[class~="datetimepicker"]').datetimepicker().inputmask("99/99/9999 99:99");

                $('.datetimepicker').parent('.input-group').on('click', '.input-group-btn', function (e) {
                    e.preventDefault();
                    $(this).parent('.input-group').find('.datetimepicker').datetimepicker('show');
                });

            });
        </script>        

    </body>
</html>
