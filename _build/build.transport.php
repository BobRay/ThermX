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


$packageNamespace = 'thermx';
/* This example assumes that you are creating one element with one namespace, a
 * lexicon, and one file resolver.  You'll need to modify it if your situation
 * is different. A snippet with no support files (no images, no css, no js
 * includes, etc.) doesn't need a file  resolver so you can comment out that
 * part of the code. If you have no lexicon, you can comment out that part of
 * the code. If you need to create multiple  elements (e.g. a snippet, several
 * chunks, and maybe a plugin) you can do it all in this file, but you'll have
 * to duplicate the code below that creates  and packages the element. You'll
 * also have to reset the variables for each segment. If you put all your
 * support files in or below in a single  directory, you'll only need one file
 * resolver.
*/

/* The name of the package as it will appear in Workspaces will be this plus
 * the next two variables */
$package_name = 'thermx';
$package_version = '3.0.1';
$package_release = 'beta';


/* Note that for file resolvers, the named directory itself is also packaged.
*  e.g. $source = /components/thermx
*  $target = MODX_ASSETS_PATH".
*/

/* Array of snippets and chunks to be created.
 * Note that these will appear in the Manager Tree in the order
 * you use here.
 */

$objectArray = array (
    array (
        /* What is it? modSnippet, modChunk, modPlugin, etc. */
        'object_type' => 'modSnippet',

        /* name of your element as it will appear in the Manager */
        'name' => 'ThermX',

        /* description field in the element's editing page */
        'description' => 'ThermX-3.0.1-beta -  Creates a Fundraising ' .
            'thermometer for your site',

        /* What's the content field called. Note: this field for chunks is also
         * called "snippet" */
        'type' => 'snippet',

        /* Where's the file PB will use to create the element */
        'source_file' => $sources['thermx'] . 'thermx.php',

        /* properties source file  */
        'props_file' => $sources['data'] . 'thermxprops.php',

        /* type of resolver */
        'resolver_type' => 'file',

        /* Files in this directory will be packaged  */
        'resolver_source' => $sources['thermx'],

         /* Those files will go here  */
        'resolver_target' => "return MODX_BASE_PATH . 'components/';"

    )
);
/*   Uncomment for debugging

foreach ($objectArray as $object) {
    echo "<br>object_type: " . $object['object_type'];
    echo "<br>name: " . $object['name'];
    echo "<br>description: " . $object['description'];
    echo "<br>type: " . $object['type'];
    echo "<br>source_file: " . $object['source_file'];
    echo "<br>category: " . $object['category'];
    echo "<br>props_file: " . $object['props_file'];
    echo "<br>resolver_source: " . $object['resolver_source'];
    echo "<br>resolver_target: " . $object['resolver_target'];
    echo "<br>";

}

die ("<br> Finished");
*/

  /* override with your own defines here (see build.config.sample.php */
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
        $modx->log(MODX_LOG_LEVEL_FATAL,'<b>Error</b> - Element source file not'
            . ' found: '.$object['source_file'].'<br />');
    }
    $modx->log(MODX_LOG_LEVEL_INFO,'Creating element from source file: ' .
            $object['source_file'].'<br />');

    /* You can get the source from the actual element in your database
     * OR manually create the object, grabbing the source from a file */
    echo '   Creating newObject of type '. $object['object_type'] . "\n";
    $c= $modx->newObject($object['object_type']);

    echo '   Setting name to ' . $object['name'] . "\n";
    $c->set('name', $object['name']);

    echo '   Setting description to ' . $object['description'] . "\n";
    $c->set('description', $object['description']);

    echo '   Setting ' . $object['type'] . ' from ' . $object['source_file']
         . "\n";

    $c->setContent(file_get_contents($object['source_file']));

    if($object['props_file'] != '') {
        $modx->log(MODX_LOG_LEVEL_INFO,'Retrieving properties from source file: ' .
            $object['props_file'].'<br />');
        require_once $object['props_file'];

        /* merge with current properties */
        $c->setProperties($properties, true);
    }

   /* create a transport vehicle for the data object */
    $attributes= array(
        XPDO_TRANSPORT_UNIQUE_KEY => 'name',
        XPDO_TRANSPORT_UPDATE_OBJECT => true,
        XPDO_TRANSPORT_PRESERVE_KEYS => false

    );
    $vehicle = $builder->createVehicle($c, $attributes);

    if ($object['resolver_source'] != '') {
        $modx->log(MODX_LOG_LEVEL_INFO,"Creating Resolver<br />");

        if ($object['resolver_type'] == 'file'
         && !is_dir($object['resolver_source'])) {
            $modx->log(MODX_LOG_LEVEL_FATAL,'<b>Error</b> - Resolver source '
                    . 'directory not found: '.$object['resolver_source']
                    . '<br />');
        }

        $modx->log(MODX_LOG_LEVEL_INFO,'Source: '.$object['resolver_source']
            . '<br />');
        $modx->log(MODX_LOG_LEVEL_INFO,'Target: '.$object['resolver_target']
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
            'source' => $sources['resolvers'] . 'install-script.php',
            'target' => "return '" . $sources['build'] . "';"

        ));
$builder->putVehicle($vehicle);



/* done building package */

/* now pack in the license file, readme and setup options */

$builder->setPackageAttributes(array(
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'setup-options' => file_get_contents($sources['build'] . 'user_input.html')
));


/* zip up the package  */
$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(MODX_LOG_LEVEL_INFO,'Package completed.<br />Execution time: '
        . $totalTime . '<br />');

exit();