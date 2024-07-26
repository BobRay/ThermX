<?php
/** This Class displays a fundraising thermometer
 @package thermx
 @author: Bob Ray
 @Copyright 2009-2017 Bob Ray
 @created: 01/15/2009
 @version 3.1.0
 * /
 *
 /*
 Adapted from a WordPress Plugin by Christopher Ross
 Original Plugin URI: http://thisismyurl.com
 Compatibility: MODX Revolution

 Usage
 -----

 Minimal Snippet Call:

 [[ThermX? &thermxProgress=`2500` &thermxMax=`7500`]]

 Parameters:

 @property thermxProgress  -- Current amount raised
 @property thermxMax       -- Fundraising goal
 @property thermxFormat    -- [optional] format argument for money_format()
                     defauts to `%(#10n`.
 @property thermxLocale    -- [optional] arg for set_locale
                     defaults to  'en_US'.

 Placeholders:

 [+thermx_progress+]    -- Put this where you want the current
                           amount raised to appear.

 [+thermx_thermometer+] -- Put this where you want the
                           thermometer to appear.

*/




require_once $modx->getOption('tx.core_path', null, $modx->getOption('core_path') . 'components/thermx/') . 'classes/thermx.class.php';
$cssUrl = $modx->getOption('tx.assets_url', null, $modx->getOption('assets_url') . 'components/thermx/') . 'css/thermx.css';
//$src = MODX_CORE_PATH . 'components/thermx/classes/thermx.class.php';
//require $src;

$thermxConfig = $scriptProperties;

$thermxProgress = isset($thermxConfig['thermxProgress']) ? $thermxConfig['thermxProgress'] : 0;

$thermxMax = isset($thermxConfig['thermxMax']) ? $thermxConfig['thermxMax'] : 12000;

/* avoid division by zero */
$thermxMax = $thermxMax = 0 ? 1 : $thermxMax;

$thermxFormat = isset($thermxConfig['thermxFormat']) ? $thermxConfig['thermxFormat'] : '%(#10n';

$thermxLocale = isset($thermxConfig['thermxLocale']) ? $thermxConfig['thermxLocale'] : 'en_US';

$therm = new thermx($modx,$thermxProgress,
    $thermxMax, $thermxFormat, $thermxLocale);

$cur = $thermxProgress;
$max = $thermxMax;


$tHeight = 400;  /* height of thermometer in px */

$percent = ($cur/$max);

/* make sure percent doesn't go over 100 */
$percent = $percent > 1? 1 : $percent;

/* height for mercury */
$ht = round($percent * $tHeight);
$mt = round($tHeight - $ht);

/* inject basis css from file */
//$src = MODX_ASSETS_URL . 'components/thermx/css/thermx.css';
$modx->regClientCSS($cssUrl);

/* inject css for mercury */
$src = '<style type="text/css">
  .percent {margin-top: ' .
    $mt . 'px; height:' . $ht . 'px;}
</style>';
$modx->regClientCSS($src);

/* current amount raised placeholder */
$modx->setPlaceholder('thermx_progress',
    $therm->showProgress());

/* thermometer graphic placeholder */
$modx->setPlaceholder('thermx_thermometer',
    $therm->showThermometer());

return'';
