<?php
/**
 * ThermX Build Script
 *
 * @name ThermX
 * @version 3.0.4
 * @release beta1
 * @author BobRay <http://bobsguides.com>
 *
 * @package thermx
 * @subpackage build
 */
global $modx;

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

$root = (dirname(dirname(__FILE__))).'/';
$core = dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/core/';
require_once($core . 'config/config.inc.php');
$sources= array (
    'root' => $root,
    'build' => $root . '_build/',
    'data' => $root . '_build/data/',
    'source_core' => $root . 'core/components/thermx',
    'source_assets' => $root . 'assets/components/thermx',
    'docs' => $root . 'core/components/thermx/docs/',
    'resolvers' => $root . '_build/resolvers/',
);
unset($root);

$package_name = 'thermx';
$package_version = '3.0.4';
$package_release = 'beta1';

$packageNamespace = 'thermx';

require_once $sources['build'] . '/build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx= new modX();
$modx->initialize('mgr');
echo '<pre>'; /* used for nice formatting for log messages  */
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');
    /* $modx->setDebug(true); */

$modx->loadClass('transport.modPackageBuilder','',false, true,'{core_path}components/'.$packageNamespace.'/');
$builder = new modPackageBuilder($modx);
$builder->createPackage($package_name,$package_version,$package_release);
$builder->registerNamespace($packageNamespace,false,true,'{core_path}components/'.$packageNamespace . '/');


/* create category */
$category= $modx->newObject('modCategory');
$category->set('id',1);
$category->set('category','ThermX');

/* add snippets */
$modx->log(modX::LOG_LEVEL_INFO,'Adding in snippets.');
$snippets = include $sources['data'].'transport.snippets.php';
if (is_array($snippets)) {
    $category->addMany($snippets);
} else { $modx->log(modX::LOG_LEVEL_FATAL,'Adding snippets failed.'); }

/* add chunks  */
/*$modx->log(modX::LOG_LEVEL_INFO,'Adding in chunks.');
$chunks = include $sources['data'].'transport.chunks.php';
if (is_array($chunks)) {
    $category->addMany($chunks);
} else { $modx->log(modX::LOG_LEVEL_FATAL,'Adding chunks failed.'); }*/

/* create category vehicle */
$attr = array(
    xPDOTransport::UNIQUE_KEY => 'category',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'Snippets' => array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'name',
        ),
        /*'Chunks' => array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'name',
        ),*/
    )
);


$vehicle = $builder->createVehicle($category,$attr);
$vehicle->resolve('file',array(
        'source' => $sources['source_core'],
        'target' => "return MODX_CORE_PATH . 'components/';",
    ));
    $vehicle->resolve('file',array(
        'source' => $sources['source_assets'],
        'target' => "return MODX_ASSETS_PATH . 'components/';",
    ));
 $vehicle->resolve('php',array(
            'type' => 'php',
            'source' => $sources['resolvers'] .
                'install-script.php',
            'target' => "return '" . $sources['build'] . "';"

        ));
$builder->putVehicle($vehicle);
/* now pack in the license file, readme.txt and setup options */
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['source_core'] . '/docs/license.txt'),
    'readme' => file_get_contents($sources['source_core'] . '/docs/readme.txt'),
    'changelog' => file_get_contents($sources['source_core'] . '/docs/changelog.txt'),
    'setup-options' => array('source' => $sources['build'].
            'user_input.html',)
));






//$builder->putVehicle($vehicle);



/* done building package */

if (false) { /* now pack in the license file,
 * readme and setup options */

    $builder->setPackageAttributes(array(
        'readme' => file_get_contents($sources['docs'] .
            'readme.txt'),
        'license' => file_get_contents($sources['docs'] .
            'license.txt'),
        'setup-options' => array('source' => $sources['build'].
            'user_input.html',
            ),
    ));
}


/* zip up the package  */
$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(modX::LOG_LEVEL_INFO,'Package completed.
        <br />Execution time: '
        . $totalTime . '<br />');

exit();