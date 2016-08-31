@echo off
date 2015-12-04
time 17:20:50
@mode con: cols=160 lines=1200
echo Testing UnifiedOrderCollectionTest...
@php C:\bin\phpunit.phar --verbose collections/UnifiedOrderCollectionTest.php
@pause

