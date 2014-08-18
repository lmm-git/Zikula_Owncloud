<form id="zikula_auth" action="#" method="post">
	<input type="hidden" name="requesttoken" value="<?php echo $_['requesttoken'] ?>" id="requesttoken">
	<fieldset class="personalblock">
		<legend><strong>Zikula authentication bridge</strong></legend>
		<p>
			<label for="zikula_server"><?php echo $l->t('Server address (with ending /)');?></label>
			<input type="text" id="zikula_server" name="zikula_server" value="<?php echo $_['zikula_server']; ?>" />

			<label for="zikula_server_www_address"><?php echo $l->t('Server address (the visitors see in their browser (www-address)) (with ending /)');?></label>
			<input type="text" id="zikula_server_www_address" name="zikula_server_www_address" value="<?php echo $_['zikula_server_www_address']; ?>" />

			<label for="zikula_server_token"><?php echo $l->t('Auth token taken from the Zikula OwnCloud module');?></label>
			<input type="password" id="zikula_server_token" name="zikula_server_token" value="<?php echo $_['zikula_server_token']; ?>" />

		</p>
		<input type="submit" name="zikula_auth" value="Save" />
	</fieldset>
</form>
