@echo off
date 2015-09-13
time 03:30:50
@mode con: cols=130 lines=800
echo Testing GroupBuyInvoiceEntityTest...
@php C:\bin\phpunit.phar --verbose extends/GroupBuyInvoiceEntityTest.php
@pause

