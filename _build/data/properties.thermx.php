<?php

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
                        'name'=>'thermxFormat',
                        'desc'=>'optional string to be passed to the money_format() function for use with alternate currencies.',
                        'type'=>'textfield',
                        'options'=>'',
                        'value'=>'%(#10n'
                    ),
                    array(
                        'name'=>'thermxLocale',
                        'desc'=>'optional string to be passed to the set_locale() function for use with alternate currencies (defaults to US dollars).',
                        'type'=>'textfield',
                        'options'=>'',
                        'value'=>'en_US'
                    )

);

return $properties;
