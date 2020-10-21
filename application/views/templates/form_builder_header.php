<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $pageTitle; ?></title>
        <link rel="icon"  type="image/png" href="<?= base_url() ?>assets/images/logo-favicon.png" />
        <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">
            <link rel='stylesheet' type='text/css'  href='<?= base_url() ?>assets/css/component.css'>
            <link rel='stylesheet' type='text/css'  href='<?= base_url() ?>assets/css/font-awesome.min.css'>
                <link href='http://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>

                    <!-- Style sheets -->
                    <link href="<?= base_url() ?>assets/form_builder/application/css/basic.css" media="screen" rel="stylesheet" type="text/css"/>
                    <link href="<?= base_url() ?>assets/form_builder/application/css/jquery-ui.css" media="screen" rel="stylesheet" type="text/css"/>
                    <link href="<?= base_url() ?>assets/form_builder/application/css/style.css" media="screen" rel="stylesheet" type="text/css"/>
                    <link id="style" href="<?= base_url() ?>assets/form_builder/application/form_resources/themes/custom/css/bootstrap.min.css" media="screen" rel="stylesheet" type="text/css"/>
                    <link id="style" href="<?= base_url() ?>assets/form_builder/application/form_resources/themes/custom/css/common.css" media="screen" rel="stylesheet" type="text/css"/>
                    <link rel='stylesheet' type='text/css'  href='<?= base_url() ?>assets/css/colorbox.css' />




                    <link type="text/css" rel="stylesheet" href="<?= base_url() ?>assets/form_builder/plugins/syntaxhighlighter_3.0.83/styles/shCoreDefault.css">
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.8.2.js"></script>
                        <style type="text/css">
                            body {
                                background: none;
                            }
                            a.logo {
                                text-decoration: none;
                                width:45px;
                            }
                            a.logo:hover{
                                color: #ffffff;
                            }
                            .HeaderWraper h1{
                                margin-bottom: 0;
                            }
                            .dropdown li a{
                                text-decoration: none;
                            }
                            .cbp-tm-icon-archive{
                                text-decoration: none;
                            }
                            .cbp-tm-icon-mobile{
                                text-decoration: none;
                            }
                            ul.dropdown{
                                margin:10px 0 0 88px;
                            }
                            #work-area{
                                min-height: 541px !important;
                            }
                        </style>


                        </head>

                        <body>
                            <input type="hidden" name="base_url" id="base_url" value='<?php echo base_url(); ?>'/>
                            <div class="headerContainer">
                                <div class="HeaderWraper">
                                    <h1>
                                        <?php
                                        if ($this->acl->hasPermission('app', 'view')) {
                                            ?>
                                            <a  class="logo" href="<?= base_url() ?>apps"></a>
                                        <?php } ?>
                                    </h1>
                                    <div style="float: left; width: 360px;" class="appclass">
                                        <a href="<?= base_url() ?>app-landing-page/<?php echo $app_id; ?>" class="" title="<?php echo $app_name; ?>">
                                            <?php
//                                                $app_name = preg_replace('/[^A-Za-z0-9]/', ' ', $app_name);
                                            //$app_name = str_replace($app_name, '_', ' ');
                                            if (strlen($app_name) > 20) {
                                                echo htmlspecialchars(substr($app_name, 0, 20) . '..');
                                            } else {
                                                echo htmlspecialchars($app_name);
                                            }
                                            $app_name = htmlspecialchars(preg_replace('/[^A-Za-z0-9]/', ' ', $app_name))
                                            ?>
                                        </a>
                                    </div>
                                    <a href="http://itu.edu.pk" target="new"><img src="<?= base_url() ?>assets/home/images/itu.png" alt="Information Technology" class="ItuLogo"/></a>
                                    <ul class="dropdown">
<?php
if (isset($app_id)) {
    $formbyapp = $this->form_model->get_form_by_app($app_id);
    $form_id = '';
    if (isset($formbyapp[0]['form_id'])) {
        $form_id = $formbyapp[0]['form_id'];
        $app_name = str_replace(' ', '-', $app_name);
        $slug = $app_name . '-' . $formbyapp[0]['app_id']
        ?>
                                                <li>
                                                    <a  href="<?= base_url() ?>application-results/<?php echo $slug; ?>">
                                                        List View
                                                    </a>
                                                </li>
                                                <li>
                                                    <a  href="<?= base_url() ?>application-map/<?php echo $slug; ?>">
                                                        Map View
                                                    </a>
                                                </li>
                                                <li>
                                                    <a  href="<?= base_url() ?>graph/dashboard/<?php echo $slug; ?>">
                                                        Graph View
                                                    </a>
                                                </li>
                                                <?php if (strpos($_SERVER['SERVER_NAME'],'dataplug.itu') == false) { ?>
                                                <li><?php
                                                    if ($formbyapp[0]['module_name'] != '') {
                                                        ?>
                                                        <a id='graph_view'  href="<?= base_url() . $formbyapp[0]['module_name']; ?>">
                                                            Dashboard
                                                        </a>
                                                    <?php } else {
                                                        ?>
                                                        <a id='graph_view'  href="javascript:void(0);">
                                                            Dashboard
                                                        </a>
                                                    <?php }
                                                    ?>
                                                </li>
        <?php }
    }
} else {
    $session_data = $this->session->userdata('logged_in');

    session_to_page($session_data, $data);
    if ($this->acl->hasSuperAdmin()) {
        $apps = $this->app_model->get_app_by_department_for_super();
    } else {
        $apps = $this->app_model->get_app_by_user($data['login_user_id']);
    }

    foreach ($apps as $app) {
        $formbyapp = $this->form_model->get_form_by_app($app['id']);
        +
                $form_id = '';
        if (isset($formbyapp[0]['form_id'])) {
            $form_id = $formbyapp[0]['form_id'];
        }
        if ($formbyapp) {
            $empty_form = 'no';
        }
        $forms = $this->form_model->get_empty_app_form($app['id']);
        if ($forms) {
            $empty_form = 'no';
        } else {
            $empty_form = 'yes';
        }
        $released = '';
        $released = $this->app_released_model->get_latest_released($app['id']);
        $filename = '';
        if ($released) {
            $filename = $released['app_file'];
        }
        $department_name = '';
        if (isset($app['department_name']))
            $department_name = $app['department_name'];
        //get latest release and existence
        $dataum['app'][] = array(
            'id' => $app['id'],
            'department_id' => $app['department_id'],
            'name' => $app['name'],
            'icon' => $app['icon'],
            'department_name' => $department_name,
            'app_file' => $filename,
            'empty_form' => $empty_form,
            'form_id' => $form_id,
        );
    }
    ?>

                                            <li><a href="javascript:void(0)">List View</a>

                                                <ul class="sub_menu">
    <?php
    if (!empty($dataum['app'])) {
        foreach ($dataum['app'] as $app_item) {
            ?>
                                                            <li>
                                                            <?php
                                                            if ($app_item['empty_form'] == 'yes') {
                                                                echo '<a href="javascript:void(0)">';
                                                                echo $app_item['name'];
                                                                echo '</a>';
                                                            } else {
                                                                ?>
                                                                    <a  href="<?= base_url() ?>form/results/<?php echo $app_item['form_id']; ?>" title="<?php echo $app_item['name'] ?>">
                                                                    <?php echo (strlen($app_item['name']) > 25) ? htmlspecialchars(substr($app_item['name'], 0, 25) . ' ...') : htmlspecialchars($app_item['name']); ?>
                                                                    </a>
                                                                </li>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                </ul>
                                            </li>
                                            <li><a href="javascript:void(0)">Map View</a>
                                                <ul>
    <?php
    if (!empty($dataum['app'])) {
        foreach ($dataum['app'] as $app_item) {
            ?>
                                                            <li>
                                                            <?php
                                                            if ($app_item['empty_form'] == 'yes') {
                                                                echo '<a href="javascript:void(0)">';
                                                                echo $app_item['name'];
                                                                echo '</a>';
                                                            } else {
                                                                ?>
                                                                    <a  href="<?= base_url() ?>form/mapview/<?php echo $app_item['form_id']; ?>" title="<?php echo $app_item['name'] ?>">
                                                                    <?php echo (strlen($app_item['name']) > 25) ? substr($app_item['name'], 0, 25) . ' ...' : $app_item['name']; ?>

                                                                    </a>
                                                                    <?php }
                                                                    ?>
                                                            </li>
                                                                <?php
                                                            }
                                                        }
                                                        ?>

                                                </ul>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">Graph View</a>
                                            </li>
<?php } ?>
                                    </ul>
                                    <ul id="cbp-tm-menu" class="cbp-tm-menu">

                                        <li>
                                            <a href="#"><img src="<?= base_url() ?>assets/images/settings-ico.png" /></a>
                                            <ul class="cbp-tm-submenu">
<?php if (isset($app_id)) { ?>
                                                    <li>
                                                        <a href="<?= base_url() ?>application-setting/<?= $app_id ?>" class="cbp-tm-icon-archive" title="<?php echo $app_name; ?>">
                                                            Settings - <?php
    $app_name = preg_replace('/[^A-Za-z0-9]/', ' ', $app_name);
    if (strlen($app_name) > 12)
        echo substr($app_name, 0, 12) . '..';
    else
        echo $app_name;
    ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?= base_url() ?>app-comments/<?= $app_id ?>" class="cbp-tm-icon-archive" title="<?php echo $app_name; ?>">
                                                            Comments - <?php
                                                        if (strlen($app_name) > 10)
                                                            echo substr($app_name, 0, 10) . '..';
                                                        else
                                                            echo $app_name;
    ?> 
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($this->acl->hasPermission('app', 'view')) { ?>
                                                    <li>

                                                        <a href="<?= base_url() ?>apps" class="cbp-tm-icon-archive">
                                                            Applications
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($this->acl->hasPermission('app_views', 'view')) { ?>

                                                    <li>
                                                        <a href="<?= base_url() ?>app/appusersview" class="cbp-tm-icon-archive">
                                                            Application Views
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($this->acl->hasPermission('app_users', 'view')) { ?>

                                                    <li>
                                                        <a href="<?= base_url() ?>app/appusers" class="cbp-tm-icon-archive">
                                                            Application Users
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($this->acl->hasPermission('department', 'view')) { ?>

                                                    <li>
                                                        <a href="<?= base_url() ?>department/index" class="cbp-tm-icon-archive">
                                                            Departments
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($this->acl->hasPermission('users', 'view')) { ?>

                                                    <li>
                                                        <a href="<?= base_url() ?>users/userlisting" class="cbp-tm-icon-archive">
                                                            Users
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($this->acl->hasPermission('groups', 'view')) { ?>

                                                    <li>
                                                        <a href="<?= base_url() ?>users/groups" class="cbp-tm-icon-archive">
                                                            Groups
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if ($this->acl->hasPermission('users', 'edit profile')) { ?>
                                                        <li>
                                                            <a href="<?= base_url() ?>user-profile/<?php echo $login_user_id; ?>" class="cbp-tm-icon-mobile">User Profile</a>
                                                        </li>
                                                        <?php } ?>
                                                <?php
                                                if ($this->acl->hasSuperAdmin()) {
                                                    ?>
                                                    <li>
                                                        <a href="<?= base_url() ?>log" class="cbp-tm-icon-mobile">Site Log</a>
                                                    </li>
                                                    <li>
                                                        <a href="<?= base_url() ?>site/sitesettings/1" class="cbp-tm-icon-mobile">Site Settings</a>
                                                    </li>
                                                <?php } ?>  
                                                <?php if (isset($app_id)) {?>
                                                <li>
                                                    <a href="<?= base_url() ?>apiappurl/<?php echo $app_id;?>" class="cbp-tm-icon-mobile">Create API for Data Feed</a>
                                                </li>
                                                <?php } ?>  
                                                <li>
                                                    <a href="<?= base_url() ?>users/logout" class="cbp-tm-icon-mobile">Logout</a>
                                                </li>
                                            </ul>
                                        </li>

                                    </ul>
                                    <div class="wlcomeBar">
                                        <p>Welcome, <?php echo $login_user_fullname; ?>
                                            &nbsp;(<?php echo $login_department_name; ?>)
                                        </p>
                                    </div>
                                </div>
                            </div>


                            <?php
                            $messages = $this->session->flashdata('validate');

                            if ($messages) {
                                if ($messages['type'] == 'success') {
                                    $type = 'flashdiv-success';
                                } else if ($messages['type'] == 'error') {
                                    $type = 'flashdiv-error';
                                } else if ($messages['type'] == 'warning') {
                                    $type = 'flashdiv-warning';
                                }
                                ?>
                                <div class="<?php echo $type; ?>">
                                    <div class="flash-nav-container">
                                        <div>
                                            <?php echo $messages['message']; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                            ?>
                            <div class="flashdiv-warning" id="check_browser" style="display: none;">
                                <div class="flash-nav-container">
                                    <div>
                                        For best results, Use <a href="http://www.mozilla.org/en-US/firefox/new/" target="_new">Mozilla Firefox</a> or <a href="https://www.google.com/intl/en/chrome/browser/" target="_new">Google Chrome</a> browser.
                                    </div>
                                </div>
                            </div>

                            <script>
                                function getBrowser() {
                                    if (navigator.userAgent.indexOf("Chrome") != -1) {
                                        return "Chrome";
                                    } else if (navigator.userAgent.indexOf("Opera") != -1) {
                                        return "Opera";
                                    } else if (navigator.userAgent.indexOf("MSIE") != -1) {
                                        return "IE";
                                    } else if (navigator.userAgent.indexOf("Firefox") != -1) {
                                        return "Firefox";
                                    } else {
                                        return "unknown";
                                    }
                                }

                                var check_browser = getBrowser();
                                if (check_browser == "IE")
                                {
                                    $('#check_browser').show()
                                }
                            </script>
                                  <?php if (strpos($_SERVER['SERVER_NAME'],'dataplug.itu') == false) { ?>                      
                            <style>
                                ul.dropdown {
                                    margin: 35px 0 0 117px !important;
                                }
                                img.ItuLogo {
                                    right: 400px;
                                }
                            </style>
<?php }?>
