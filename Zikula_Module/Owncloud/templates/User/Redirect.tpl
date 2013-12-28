<h3>{gt text='Redirecting you to Owncloud... Please wait'}</h3>

<iframe id="Owncloud_Redirection_Logout_Iframe" src={$url_logout} height="0" width="0"></iframe>
<div style="display: none">

	<form method="post" name="login" id="Owncloud_Redirection_Login_Form" action="{$url}">
		<fieldset>
			<input type="text" name="user" id="user" value="{$uname}" autocomplete="off" />
			<input type="password" name="password" id="password" value="{$authcode.authcode}" />

			<input type="checkbox" name="remember_login" value="0" id="remember_login" checked />
			<input type="hidden" name="timezone-offset" id="timezone-offset"/>
			<input type="submit" id="submitbutton" value="Login"/>
		</fieldset>
	</form>
</div>
<script type="text/javascript">
	window.onload = function Oncloud_submit() {
		var iframe = document.getElementById('Owncloud_Redirection_Logout_Iframe');
		iframe.src = iframe.src + '#';
		setTimeout('document.getElementById(\'Owncloud_Redirection_Login_Form\').submit();', 1000);
	}
</script>
