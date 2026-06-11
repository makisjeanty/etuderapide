@echo off
set PHPRC=%~dp0php.ini
echo Iniciando Makis Digital com Seguranca Maxima...
"C:\php-8.4\php.exe" -c "%~dp0php.ini" -S 127.0.0.1:8000 -t "%~dp0public" "%~dp0server.php"
