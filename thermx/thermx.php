<?php
/** This Class displays a fundraising thermometer
 @package thermx
 @author: Bob Ray
 @created: 01/15/2009
 @version 1.0.0
 Adapted from a Wordpress Plugin by Christopher Ross
 Original Plugin URI: http://thisismyurl.com
 Compatibility: MODx Revolution

 Usage
 -----

 Minimal Snippet Call:

 [[ThermX? &thermxProgress=`2500` &thermxMax=`7500`]]

 Parameters:

 &thermxProgress  -- Current amount raised
 &thermxMax       -- Fundraising goal
 &thermxFormat    -- [optional] format argument for money_format()
                     defauts to `%(#10n`.
 $thermxLocale    -- [optional] arg for set_locale
                     defaults to  'en_US'.

 Placeholders:

 [+thermx_progress+]    -- Put this where you want the current
                           amount raised to appear.

 [+thermx_thermometer+] -- Put this where you want the
                           thermometer to appear.

*/

/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



$src = $modx->config['base_path'] . 'components/thermx/classes/thermx.class.php';
require $src;

$thermxConfig = $scriptProperties;

$thermxProgress = isset($thermxConfig['thermxProgress']) ? $thermxConfig['thermxProgress'] : 0;
$thermxMax = isset($thermxConfig['thermxMax']) ? $thermxConfig['thermxMax'] : 12000;

/* avoid division by zero */
$thermxMax = $thermxMax = 0 ? 1 : $thermxMax;

$thermxFormat = isset($thermxConfig['thermxFormat']) ? $thermxConfig['thermxFormat'] : '%(#10n';
$thermxLocale = isset($thermxConfig['thermxLocale']) ? $thermxConfig['thermxLocale'] : 'en_US';

$therm = new thermx($modx,$thermxProgress, $thermxMax, $thermxFormat, $thermxLocale);

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
$src = $modx->config['base_url'] . 'components/thermx/style.css';
$modx->regClientCSS($src);

/* inject css for mercury */
$src = '<style type="text/css">
  .percent {margin-top: ' . $mt . 'px; height:' . $ht . 'px;}
</style>';
$modx->regClientCSS($src);

/* current amount raised placeholder */
$modx->setPlaceholder('thermx_progress', $therm->showProgress());

/* thermometer graphic placeholder */
$modx->setPlaceholder('thermx_thermometer', $therm->showThermometer());

return;
?>