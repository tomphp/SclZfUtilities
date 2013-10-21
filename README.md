SclZfUtilities
==============

[![Build Status](https://travis-ci.org/SCLInternet/SclZfUtilities.png?branch=master)](https://travis-ci.org/SCLInternet/SclZfUtilities)

Some useful utility classes, plugins and view helpers.

WARNING
=======

This is currently a very volitile module, a lot of stuff gets added and later moved
to their own modules so if you decide to use this module in it's current state
be prepared for things to change.

Controller plugins
==================

formSubmitted
-------------

This is a quick way to check if a form has been posted and the content is valid.

To use it in your controller simple do

```php
if ($this->formSubmitted($myForm)) {
    doWhatNeedsToBeDone();
}
````
