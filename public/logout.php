<?php
require_once '../classes/User.class.php';

User::logout();


header("Location:index.php");