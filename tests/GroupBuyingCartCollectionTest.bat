@echo off
date 2015-09-13
time 03:30:50
@mode con: cols=160 lines=1200
echo Testing GroupBuyingActivityCollection...
@php C:\bin\phpunit.phar --verbose collections/EC/Skygo/GroupBuyingCartCollectionTest.php
@pause

