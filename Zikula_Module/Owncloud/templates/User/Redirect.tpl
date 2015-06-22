{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='jquery-ui'}
{pageaddvar name='javascript' value='modules/Owncloud/javascript/User/RedirectPulsate.js'}
<h3>{gt text='Redirecting you to ownCloud. Please wait...'}</h3>
<div id="Owncloud_redirectLogo" class="z-center">
	{img modname='Owncloud' src='logo-big.png' style='max-width: 100%;' height='auto'}
</div>

<div style="display: none">

	<form method="post" name="login" id="Owncloud_Redirection_Login_Form" action="{$url}">
		<fieldset>
			<input type="text" name="user" id="user" value="{$uname}" autocomplete="off" />
			<input type="text" name="password" id="password" value="{$authcode.authcode}" autocomplete="off" />

			<input type="checkbox" name="remember_login" value="0" id="remember_login"/>
			<input type="hidden" name="requesttoken" value="dummy" />
			<input type="hidden" name="timezone-offset" id="timezone-offset" />
			<input type="submit" id="submitbutton" value="Login" />
		</fieldset>
	</form>
</div>
<script type="text/javascript">
	window.onload = function Owncloud_submit() {
		setTimeout('document.getElementById(\'Owncloud_Redirection_Login_Form\').submit();', 10);
	}
</script>
