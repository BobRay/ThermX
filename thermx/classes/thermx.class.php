<?php
/**
@package thermx
 @author: Bob Ray
 @created: 01/15/2009
 @version 3.0.1
 Adapted from a Wordpress Plugin by Christopher Ross
 Original Plugin URI: http://thisismyurl.com
 Compatibility: MODx Revolution

*/
  class thermx {

    /**
     * @var array MODx instance passed in the constructor.
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
     * PHP4 Constructor
     * @access public
     * @param array $modx MODx object.
     * @param int $prog current funds raised.
     * @param int $max Fundraising goal.
     * @param string $format string to be passed to money_format()
     * @param string $locale string to be passed to setlocale()
     */
      function thermx($modx, $prog, $max, $format = '%(#10n', $locale = 'en_US') {
          $this->__construct($modx, $prog,$max, $format. $locale);
      }

    /**
     * PHP5 Constructor
     * @access public
     * @param array $modx MODx object.
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
   $mod = $num % $nearest;
   if ($mod >= 0)
     $ret = ( $mod > ( $nearest / 2)) ? $num + ( $nearest - $mod) : $num - $mod;
    else
     $ret = ( $mod > (-$nearest / 2)) ? $num - $mod : $num + ( -$nearest - $mod);
    return $ret;
}

} /* end of class thermx */
?>
