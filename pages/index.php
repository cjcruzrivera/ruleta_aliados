<?php
session_start();

if (!$_SESSION['logged']) {
    header('location: ./admin/login.php');
} else {
    header('location: ./admin/participaciones.php');
}