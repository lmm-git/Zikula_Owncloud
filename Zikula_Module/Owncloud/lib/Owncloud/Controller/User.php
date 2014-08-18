<?php
class Owncloud_Controller_User extends Zikula_AbstractController
{
	/**
	 * general settings
	 *
	 * @version 1.1
	 * @author Leonard Marschke
	 * @return redirection to owncloud
	 */
	public function redirect()
	{
		if (!SecurityUtil::checkPermission('Owncloud::Use', '::', ACCESS_EDIT)) {
			return LogUtil::registerPermissionError();
		}
		
		//generate a auth-var
		$chars .= '0123456789';
		$chars .= 'abcdefghijklmnopqrstuvwxyz';
		$chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$genstring = '';
		
		srand((double)microtime()*1000000);
		$i = 0;
		while ($i < 50) {
			$num = rand() % strlen($chars);
			$tmp = substr($chars, $num, 1);
			$genstring .= $tmp;
			$i++;
		}
		$authcode = array('usebefore' => new DateTime('+1 Minute'), 'authcode' => $genstring);
		UserUtil::setVar('owncloud_authcode', serialize($authcode));
		$url = $this->getVar('OwncloudURL') . 'index.php?zikula_authcode=' . urlencode($genstring);
		$url_logout = $this->getVar('OwncloudURL') . 'index.php?logout=true';
		
		$owncloudLoginPage = fopen($this->getVar('OwncloudURL') . '/index.php', "rb");
		$owncloudLoginPage = stream_get_contents($owncloudLoginPage);
		preg_match('/(.*)data-requesttoken="(?P<requesttoken>\w+)"/', $owncloudLoginPage, $matches);
		
		$this->view->assign('requesttoken', $matches['requesttoken']);
		$this->view->assign('url_logout', $url_logout);
		$this->view->assign('url', $url);
		$this->view->assign('uname', UserUtil::getVar('uname'));
		$this->view->assign('authcode', $authcode);
		
		return $this->view->fetch('User/Redirect.tpl');
	}
}
