<?php

/**
 * Default properties for the ThermX package
 * @author Bob Ray
 * 1/15/11
 *
 * @package thermx
 * @subpackage build
 */


$properties = array (
   array (
                        'name'=>'thermxProgress',
                        'desc'=>'Amount raised so far',
                        'type'=>'integer',
                        'options'=>'',
                        'value'=>'0'
                    ),


                    array(
                        'name'=>'thermxMax',
                        'desc'=>'Fundraising goal',
                        'type'=>'integer',
                        'options'=>'',
                        'value'=>'10000'
                    ),

                    array(
                        'name'=>'thermxLocale',
                        'desc'=>'optional string to be passed to the set_locale() function for use with alternate currencies (defaults en_US).',
                        'type'=>'textfield',
                        'options'=>'',
                        'value'=>'en_US'
                    )

);

return $properties;
