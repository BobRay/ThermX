<?php
/**
 * ThermX package install resolver script
 *
 * Copyright 2011-2017 Bob Ray
 * @author Bob Ray <https://bobsguides.com>
 * 1/15/11
 *
 * ThermX is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * ThermX is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * ThermX; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package thermx
 */
/**
 * Description ThermX package install resolver script
 * @package thermx
 * @subpackage build
 */
/**
 * Install resolver for
 *
 * @package thermx
 * @subpackage build
 */
$success = false;
$prefix = $modx->getVersionData()['version'] >= 3
    ? 'MODX\Revolution\\'
    : '';

switch($options[xPDOTransport::PACKAGE_ACTION]) {

    case xPDOTransport::ACTION_INSTALL:
        /* Create Sample Thermometer Page resource if user wants it */
        if (isset($options['install_sample']) && $options['install_sample'] == 'Yes' ) {

            $txt = file_get_contents($object->xpdo->config['core_path'] . 'components/thermx/docs/samplepage.html');


            $default_template = $object->xpdo->config['default_template'];
            $object->xpdo->log(xPDO::LOG_LEVEL_INFO,"Creating resource: Sample Thermometer Page<br />");
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
        case xPDOTransport::ACTION_UPGRADE:
            $success = true;
            break;
        case xPDOTransport::ACTION_UNINSTALL:
            $object->xpdo->log(xPDO::LOG_LEVEL_WARN,"<br /><b>NOTE: You will have to remove the Sample Thermometer Resource manually</b><br />");
            $success = true;
            break;

}
return $success;
