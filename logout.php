<?php
session_start();
if (session_destroy()) { //hapus session
    header('location:index.php');
}