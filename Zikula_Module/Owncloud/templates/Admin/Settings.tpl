{include file='Admin/Includes/Header.tpl' __title='Settings'}

{form cssClass='z-form'}
	{formerrormessage id='error'}
	{formvalidationsummary}

	<fieldset>
		<legend>{gt text='Server authentification details'}</legend>
		<div class="z-formrow">
			{formlabel __text='IP-Address of owncloud instance' for='AllowedHost' mandatorysym=true}
			{formtextinput id='AllowedHost' maxLength='255' mandatory=true}
		</div>

		<div class="z-formrow">
			{formlabel __text='URL of owncloud instance' for='OwncloudURL' mandatorysym=true}
			{formtextinput id='OwncloudURL' maxLength='255' mandatory=true}
		</div>

		<div class="z-formrow">
			{formlabel __text='Auth token' for='Authtoken'}
			{formtextinput id='Authtoken' minLength='15' maxLength='255' mandatory=true}
		</div>

	</fieldset>
	
	
	<div class="z-buttons z-formbuttons">
		{formbutton commandName='send' __text='Store settings' class='z-bt-ok z-btgreen'}
		{formbutton commandName='cancel' __text='Cancel' class='z-bt-cancel z-btred'}
	</div>
	
{/form}

{include file='Admin/Includes/Footer.tpl'}
