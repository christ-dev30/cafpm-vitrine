@echo off
REM ==========================================================================
REM  LANCER LE SITE CAFPM EN LOCAL (sans XAMPP)
REM  --------------------------------------------------------------------------
REM  Double-cliquez sur ce fichier : le site s'ouvre sur http://localhost:8000
REM  Pour arrêter le serveur : fermez cette fenêtre (ou Ctrl + C).
REM  Prérequis : le serveur MySQL doit être démarré (celui de MySQL Workbench).
REM ==========================================================================

REM Chemin vers PHP (celui de Laragon). Si "php" est dans le PATH, il est utilisé.
set PHP=C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe
where php >nul 2>nul && set PHP=php

REM Se place dans le dossier du site (là où se trouve ce fichier)
cd /d "%~dp0"

echo Site CAFPM disponible sur http://localhost:8000
start "" http://localhost:8000
"%PHP%" -S localhost:8000 router.php
