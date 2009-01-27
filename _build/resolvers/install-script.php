<?php
/**
 * Install resolver for
 *
 * @package thermx
 * @subpackage build
 */
$success = false;
switch($options[XPDO_TRANSPORT_PACKAGE_ACTION]) {

    case XPDO_TRANSPORT_ACTION_INSTALL:
        /* Create Sample Thermometer Page resource if user wants it */
        if (isset($options['install_sample']) && $options['install_sample'] == 'Yes' ) {

            $txt = file_get_contents($object->xpdo->config['base_path'] . 'components/thermx/docs/samplepage.html');


            $default_template = $object->xpdo->config['default_template'];
            $object->xpdo->log(XPDO_LOG_LEVEL_INFO,"Creating resource: Sample Thermometer Page<br />");
            $r = $object->xpdo->newObject('modResource');
            $r->set('class_key','modDocument');
            $r->set('context_key','web');
            $r->set('type','document');
            $r->set('contentType','text/html');
            $r->set('pagetitle','Sample Thermometer');
            $r->set('longtitle','Sample Thermometer Page');
            $r->set('description','Sample Thermometer Page');
            $r->set('alias','thermometer');
            $r->set('published','1');
            $r->set('parent','0');
            $r->set('isfolder','0');
            $r->setContent($txt);
            $r->set('richtext','0');
            $r->set('menuindex','99');
            $r->set('searchable','1');
            $r->set('cacheable','1');
            $r->set('menutitle','thermometer');
            $r->set('donthit','0');
            $r->set('hidemenu','0');
            $r->set('template',$default_template);

            $r->save();
        }
              $success =  true;
            break;
        case XPDO_TRANSPORT_ACTION_UPGRADE:
            $success = true;
            break;
        case XPDO_TRANSPORT_ACTION_UNINSTALL:
            $object->xpdo->log(XPDO_LOG_LEVEL_INFO,"<br /><b>NOTE: You will have to remove the Sample Thermometer Resource manually</b><br />");
            $success = true;
            break;

}
return $success;