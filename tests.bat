@echo off
date 2015-12-04
time 17:20:50
@mode con: cols=160 lines=1200
@php C:\bin\phpunit.phar --bootstrap framework/autoload.php tests
@pause

