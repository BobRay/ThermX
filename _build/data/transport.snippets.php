<?php
/**
 * @package thermx
 * @subpackage build
 */

function getSnippetContent($filename) {
    $o = file_get_contents($filename);
    $o = str_replace('<?php','',$o);
    $o = str_replace('?>','',$o);
    $o = trim($o);
    return $o;
}
$snippets = array();

$snippets[0]= $modx->newObject('modSnippet');
$snippets[0]->fromArray(array(
    'id' => 0,
    'name' => 'ThermX',
    'description' => 'ThermX-3.0.3-beta1 - Fundraising thermometer snippet.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.thermx.php'),
),'',true,true);
$properties = include $sources['data'].'properties.thermx.php';
$snippets[0]->setProperties($properties);

unset($properties);

return $snippets;