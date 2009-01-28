ThermX Fundraising Snippet
==========================
Author: BobRay <bobray@softville.com>
Date:   01/10/2009
====================

This snippet displays a fundraising thermometer and a
text display of the amount raised so far.

Minimal Snippet Call:
=====================

 [[ThermX? &thermxProgress=`2500` &thermxMax=`7500`]]

Parameters:
===========

 &thermxProgress  -- Current amount raised
 &thermxMax       -- Fundraising goal
 &thermxFormat    -- [optional] format argument for
                        money_format() function
                        defauts to `%(#10n`.
 &thermxLocale    -- [optional] format argument for
                        set_locale() function.
                        defaults to 'en_US'

 Placeholders:
 =============

 [[+thermx_progress]]    -- Put this where you want the
                           current amount raised to appear.

 [[+thermx_thermometer]] -- Put this where you want the
                           thermometer to appear.

 Example Page Content:
 =====================

 [[ThermX? &thermxProgress=`2500` &thermxMax=`7500`]]

 <p>Our progress so far: [[+thermx_progress]]</p>


 [[+thermx_thermometer]]

