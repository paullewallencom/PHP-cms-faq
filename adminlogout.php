<?php
session_start();
require("config.php");
session_unregister('SESS_ADMINUSER');
session_unregister('SESS_ADMINID');
header("Location: " . $config_basedir);
?>