ThermX Fundraising Thermometer Snippet
======================================

**Author:** Bob Ray [Bob's Guides](http://bobsguides.com)
**Date:**   01/10/2011

**Tutorial:** [Bob's Guides](http://bobsguides.com/thermx-tutorial.html)  
**Bugs and Requests:** [GitHub](https://github.com/BobRay/ThermX/issues)  
**Styling:** assets/components/thermx/css/thermx.css  

This snippet for MODx Revolution displays a fundraising thermometer and a
text display of the amount raised so far.

##Minimal Snippet Call


     [[!ThermX? &thermxProgress=`2500` &thermxMax=`7500`]]

##Parameters

    &thermxProgress  -- Current amount raised  

    &thermxMax       -- Fundraising goal  

    &thermxFormat    -- [optional] format argument for  
                        money_format() function  
                        defauts to `%(#10n`.  

    &thermxLocale    -- [optional] format argument for  
                        set_locale() function.  
                        defaults to 'en_US'  

##Placeholders:
 
     [[+thermx_progress]]    -- Put this where you want the  
                                current amount raised to appear.  

     [[+thermx_thermometer]] -- Put this where you want the  
                                thermometer to appear.  

##Example Page Content

    [[ThermX? &thermxProgress=`2500` &thermxMax=`7500`]]   

    <p>Our progress so far: [[+thermx_progress]]</p>  

    [[+thermx_thermometer]]  

