@echo off
date 2015-11-11
time 03:30:50
@mode con: cols=130 lines=800
echo Testing GroupBuyPickupEntityTest...
@php C:\bin\phpunit.phar --verbose extends/GroupBuyPickupEntityTest.php
@pause

