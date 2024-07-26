<?php
/**
 * ThermX Class
 *
 * Copyright 2011-2024 Bob Ray
 *
 * @author Bob Ray <https://bobsguides.com>
 * 5/11/11
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
 * MODX ThermX Class
 *
 * Description Class file for ThermX package
 * @package thermx
 */

/*
 Adapted from a WordPress Plugin by Christopher Ross
 Original Plugin URI: http://thisismyurl.com
 Compatibility: MODX Revolution

*/
  class thermx {

    /**
     * @var array MODX instance passed in the constructor.
     * @access protected
     */
    var $modx;
/**
     * @var int Current funds raised
     * @access protected
     */

   var $thermxProgress;

/**
     * @var int Fundraising goal
     * @access protected
     */

   var $thermxMax;
/**
     * @var string format string to be passed
     * to money_format()
     * @access protected
     */
   var $thermxFormat;

/**
     * @var string format string to be passed
     * to set_locale()
     * @access protected
     */
   var $thermxLocale;


    /**
     * PHP5 Constructor
     * @access public
     * @param array $modx MODX object.
     * @param int $prog current funds raised.
     * @param int $max Fundraising goal.
     * @param string $format string to be passed to money_format()
     */
      function __construct($modx, $prog, $max, $format = '%(#10n' , $locale = 'en_US') {
          $this->thermxProgress = $prog;
          $this->thermxMax = $max;
          $this->thermxFormat = $format;
          $this->thermxLocal = $locale;
      }

   /**
   * Formats money amount
   *
   * @param string $format - format string for money_format()
   * @param int $num - Dollar amount
   *
   * Note: Some systems do not have the money_format()
   * function. For those systems, using the thermxFormat
   * parameter will have no effect.
   */

    function my_money_format($format, $num) {
        if (function_exists('money_format')) {
            setlocale(LC_MONETARY, $this->thermxLocale);
            return (money_format($format,$num));
        } else {
            return "$" . number_format($num, 2);
        }
    }

/**
* prints amount currently raised
* @access public
*/

function showProgress() {
    return $this->my_money_format ($this->thermxFormat,$this->thermxProgress);

}
/**
* Displays Thermometer
* @access public
*/
function showThermometer() {
    $current = $this->thermxProgress;
    $max = $this->thermxMax;

    $output = "";
    if ($current >= $max) {
        $output .= "<div class='thermx-progress-burst'>\n";
    } else {
        $output .= "<div class='thermx-progress'>\n";
    }
    $output .= "<div class='thermx-progressgraphics'>\n";

    //$percent = round(($current/$max)*100);
    //$percent = ($percent > 100) ? 100 : $percent;
    //$percent = str_pad($this->roundnum($percent, 10), 2, "0", STR_PAD_LEFT);




    //$output .= "    <div class='thermx-progressmercury percent$percent'>\n";
    $output .= "    <div class='thermx-progressmercury percent'>\n";
    $output .= "        <div class='thermx-progressmercurytop'></div>\n";
    $output .= "    </div>\n";
    $output .= "</div>\n";
    $output .= "<div class='thermx-progressnumbers'>\n";
    for ( $counter = $this->thermxMax; $counter >= 0;$counter=$counter-($this->thermxMax/10)    ) {
        $output .= "<div class='thermx-progressvalue'>".$this->my_money_format ($this->thermxFormat,$counter)."</div>\n";

    }
    $output .= "</div>\n";
    $output .= "<!-- Adapted from the Wordpress Our Progress plug-in by Christopher Ross, http://www.thisismyurl.com -->\n";
    $output .= "</div>\n";
    return $output;
}
/**
* Rounds off fundraising amount to nearest ten percent.
*
* @param int $num
* @param int $nearest
* @access protected
*/
function roundnum ($num, $nearest)
{
   $ret = 0;
   $txmod = $num % $nearest;
   if ($txmod >= 0)
     $ret = ( $txmod > ( $nearest / 2)) ? $num + ( $nearest - $txmod) : $num - $txmod;
    else
     $ret = ( $txmod > (-$nearest / 2)) ? $num - $txmod : $num + ( -$nearest - $txmod);
    return $ret;
}

} /* end of class thermx */
