@echo off
date 2015-03-04
time 17:20:50
@mode con: cols=130 lines=800
echo Testing GernalReturnedSalesEntityTest...
@php C:\bin\phpunit.phar --verbose extends/GernalReturnedSalesEntityTest.php
@pause

