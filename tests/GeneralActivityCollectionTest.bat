@echo off
date 2015-10-18
time 03:30:50
@mode con: cols=160 lines=1200
echo Testing GeneralActivityCollection...
@php C:\bin\phpunit.phar --verbose collections/GeneralActivityCollectionTest.php
@pause

