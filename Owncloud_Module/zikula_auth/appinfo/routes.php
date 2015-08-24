<?php
$this->create('ZikulaAuth_backToWebsite', '/')
	->actionInclude('zikula_auth/backtowebsite.php');

$this->create('ZikulaAuth_getToken', '/getToken')
	->actionInclude('zikula_auth/getToken.php');