<?php
/**
 * ThermX Build Script
 *
 * @name SPform
 * @version 3.0.1
 * @release beta
 * @author BobRay <bobray@softville.com>
 */
global $modx;

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

$root = dirname(dirname(__FILE__)).'/';
$sources= array (
    'root' => $root,
    'thermx' => $root . 'thermx/',
    'build' => $root . '_build/',
    'lexicon' => $root . '_build/lexicon/',
    'data' => $root . '_build/data/',
    'resolvers' => $root . '_build/resolvers/',
    'docs' => $root . 'thermx/docs/',
);
unset($root);

$package_name = 'thermx';
$package_version = '3.0.1';
$package_release = 'beta';

$packageNamespace = 'thermx';

/* Note that for file resolvers, the named
 * directory itself is also packaged.
 */

/* Array of snippets and chunks to be created.
 * Note that these will appear in the Manager Tree
 *  in the order you use here.
 */

$objectArray = array (
    array (

        'object_type' => 'modSnippet',

        'name' => 'ThermX',

        'description' => 'ThermX-3.0.1-beta - '.
            'Creates a Fundraising ' .
            'thermometer for your site',

        'type' => 'snippet',
        'source_file' => $sources['thermx'] . 'thermx.php',

        'props_file' => $sources['data'] .
            'thermxprops.php',

        'resolver_type' => 'file',

        'resolver_source' => $sources['thermx'],

        'resolver_target' => "return MODX_BASE_PATH .
            'components/';"

    )
);


require_once dirname(__FILE__).'/build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx= new modX();
$modx->initialize('mgr');
echo '<pre>'; /* used for nice formatting for log messages  */
$modx->setLogLevel(MODX_LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');
    /* $modx->setDebug(true); */

$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage($package_name,$package_version,$package_release);
$builder->registerNamespace($packageNamespace,false,true);

/* loop to create snippets, and chunks  */

foreach($objectArray as $object) {

    if (!file_exists($object['source_file'])) {
        $modx->log(MODX_LOG_LEVEL_FATAL,
            '<b>Error</b> - Element source file not'
            . ' found: '.$object['source_file'].'<br />');
    }
    $modx->log(MODX_LOG_LEVEL_INFO,
            'Creating element from source file: ' .
            $object['source_file'].'<br />');

    echo '   Creating newObject of type '.
        $object['object_type'] . "\n";

    $c= $modx->newObject($object['object_type']);

    echo '   Setting name to ' .
        $object['name'] . "\n";

    $c->set('name', $object['name']);

    echo '   Setting description to ' .
        $object['description'] . "\n";
    $c->set('description', $object['description']);

    echo '   Setting ' . $object['type'] .
        ' from ' . $object['source_file']
         . "\n";

    $c->setContent(file_get_contents($object['source_file']));

    if($object['props_file'] != '') {
        $modx->log(MODX_LOG_LEVEL_INFO,
            'Retrieving properties from source file: ' .
            $object['props_file'].'<br />');

        require_once $object['props_file'];

        /* merge with current properties */
        $c->setProperties($properties, true);
    }

   /* create a transport vehicle for the data object */
    $attributes= array(
        XPDO_TRANSPORT_UNIQUE_KEY => 'name',
    XPDO_TRANSPORT_PRESERVE_KEYS => false,
    XPDO_TRANSPORT_UPDATE_OBJECT => true

    );
    $vehicle = $builder->createVehicle($c, $attributes);

    if ($object['resolver_source'] != '') {
        $modx->log(MODX_LOG_LEVEL_INFO,
            "Creating Resolver<br />");

        if ($object['resolver_type'] == 'file'
         && !is_dir($object['resolver_source'])) {
            $modx->log(MODX_LOG_LEVEL_FATAL,
                    '<b>Error</b> - Resolver source '
                    . 'directory not found: '.
                    $object['resolver_source']
                    . '<br />');
        }

        $modx->log(MODX_LOG_LEVEL_INFO,
            'Source: '.$object['resolver_source']
            . '<br />');

        $modx->log(MODX_LOG_LEVEL_INFO,
            'Target: '.$object['resolver_target']
            . '<br /><br />');

        $vehicle->resolve($object['resolver_type'],array(
            'type' => $object['resolver_type'],
            'source' => $object['resolver_source'],
            'target' => $object['resolver_target'],
        ));

    }

    $builder->putVehicle($vehicle);

    unset($c);
}

/* done adding snippets and chunks */




 $vehicle->resolve('php',array(
            'type' => 'php',
            'source' => $sources['resolvers'] .
                'install-script.php',
            'target' => "return '" . $sources['build'] . "';"

        ));
$builder->putVehicle($vehicle);



/* done building package */

/* now pack in the license file,
 * readme and setup options */

$builder->setPackageAttributes(array(
    'readme' => file_get_contents($sources['docs'] .
        'readme.txt'),
    'license' => file_get_contents($sources['docs'] .
        'license.txt'),
    'setup-options' => file_get_contents($sources['build'].
        'user_input.html')
));


/* zip up the package  */
$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(MODX_LOG_LEVEL_INFO,'Package completed.
        <br />Execution time: '
        . $totalTime . '<br />');

exit();