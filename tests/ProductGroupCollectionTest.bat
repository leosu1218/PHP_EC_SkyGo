@echo off
date 2015-09-13
time 03:30:50
@mode con: cols=160 lines=1200
echo Testing ProductGroupCollectionTest...
@php C:\bin\phpunit.phar --verbose collections/ProductGroupCollectionTest.php
@pause

