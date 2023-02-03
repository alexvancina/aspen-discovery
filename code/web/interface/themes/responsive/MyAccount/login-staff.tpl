{strip}
<div id="page-content" class="col-xs-12">
	<h1>{translate text='Sign in to your staff account' isPublicFacing=true}</h1>
	<div id="loginFormWrapper">
		{if !empty($message)}{* Errors for Full Login Page *}
			<p class="alert alert-danger" id="loginError" >{translate text=$message isPublicFacing=true isPublicFacing=true}</p>
		{else}
			<p class="alert alert-danger" id="loginError" style="display: none"></p>
		{/if}
		<p class="alert alert-danger" id="cookiesError" style="display: none">{translate text="It appears that you do not have cookies enabled on this computer.  Cookies are required to access account information." isPublicFacing=true}</p>
		<p class="alert alert-info" id="loading" style="display: none">
			{translate text="Logging you in now. Please wait." isPublicFacing=true}
		</p>
		{if !empty($offline)}
			<div class="alert alert-warning">
				<p>
					{translate text=$offlineMessage isPublicFacing=true}
				</p>
			</div>
		{/if}
		{if !(empty($ssoService)) && $ssoService != 'ldap'}
		{* ldap uses the regular login form *}
            {include file='MyAccount/sso-login.tpl'}
            {if $ssoLoginOptions == 0}
	            <div class="hr-label">
	                <span class="text">{translate text="or" isPublicFacing=true}</span>
	            </div>
            {/if}
        {/if}
        {if $ssoLoginOptions == 0}
	        <form method="post" action="/MyAccount/Home" id="loginForm" class="form-horizontal">
		        <div id="missingLoginPrompt" style="display: none">{translate text="Please enter both %1% and %2%." 1=$usernameLabel 2=$passwordLabel translateParameters=true isPublicFacing=true}</div>
		        <div id="loginFormFields">
		            <div id="loginUsernameRow" class="form-group">
		                <label for="username" class="control-label col-xs-12 col-sm-4">{translate text="$usernameLabel" isPublicFacing=true} </label>
		                <div class="col-xs-12 col-sm-8">
		                    <input type="text" name="username" id="username" value="{if !empty($username)}{$username|escape}{/if}" size="28" class="form-control" maxlength="60">
		                </div>
		            </div>
		            <div id="loginPasswordRow" class="form-group">
		                <label for="password" class="control-label col-xs-12 col-sm-4">{translate text="$passwordLabel" isPublicFacing=true} </label>
		                <div class="col-xs-12 col-sm-8">
		                    <input type="password" name="password" id="password" size="28" onkeypress="return AspenDiscovery.submitOnEnter(event, '#loginForm');" class="form-control" maxlength="60">
		                    {if $ssoLoginOptions != 1 && empty($ssoService)}
		                    {* disable forgot password if sso only since its managed else where *}
			                    {if $forgotPasswordType != 'null' && $forgotPasswordType != 'none'}
			                        <p class="text-muted help-block">
			                            <strong>{translate text="Forgot %1%?" 1=$passwordLabel isPublicFacing=true}</strong>&nbsp;&nbsp;
			                            {if $forgotPasswordType == 'emailAspenResetLink'}
			                                <a href="/MyAccount/InitiateResetPin">{translate text="Reset My %1%" 1=$passwordLabel isPublicFacing=true}</a>
			                            {elseif $forgotPasswordType == 'emailResetLink'}
			                                <a href="/MyAccount/EmailResetPin">{translate text="Reset My %1%" 1=$passwordLabel isPublicFacing=true}</a>
			                            {else}
			                                <a href="/MyAccount/EmailPin">{translate text="Email my %1%" 1=$passwordLabel isPublicFacing=true}</a>
			                            {/if}
			                        </p>
			                    {/if}
		                    {/if}
		                </div>
		            </div>
		            {if !(empty($loginNotes))}
		                <div id="loginNotes" class="form-group">
		                    <div class="col-xs-12 col-sm-offset-4 col-sm-8">
		                        {translate text=$loginNotes isPublicFacing=true}
		                    </div>
		                </div>
		            {/if}
		            <div id="loginPasswordRow2" class="form-group">
		                <div class="col-xs-12 col-sm-offset-4 col-sm-8">
		                    <label for="showPwd" class="checkbox">
		                        <input type="checkbox" id="showPwd" name="showPwd" onclick="return AspenDiscovery.pwdToText('password')">
		                        {translate text="Reveal Password" isPublicFacing=true}
		                    </label>

							{if $ssoLoginOptions != 1 && empty($ssoService)}
			                    {if empty($inLibrary) && !$isOpac && !$isStandalonePage}
			                        <label for="rememberMe" class="checkbox">
			                            <input type="checkbox" id="rememberMe" name="rememberMe">
			                            {translate text="Keep Me Signed In" isPublicFacing=true}
			                        </label>
			                    {/if}
		                    {/if}
		                </div>
		            </div>

		            <div id="loginActions" class="form-group">
		                <div class="col-xs-12 col-sm-offset-4 col-sm-8">
		                {if !empty($ldapLabel)}
		                    <input type="submit" name="submit" value="{translate text="Sign in with %1%" 1=$ldapLabel isPublicFacing=true}" id="loginFormSubmit" class="btn btn-primary" onclick="return AspenDiscovery.Account.preProcessLogin();">
		                {else}
		                	<input type="submit" name="submit" value="{translate text="Login" isPublicFacing=true}" id="loginFormSubmit" class="btn btn-primary" onclick="return AspenDiscovery.Account.preProcessLogin();">
		                {/if}
		                    {if !empty($followupModule)}<input type="hidden" name="followupModule" value="{$followupModule}">{/if}
		                    {if !empty($followupAction)}<input type="hidden" name="followupAction" value="{$followupAction}">{/if}
		                    {if !empty($recordId)}<input type="hidden" name="recordId" value="{$recordId|escape:"html"}">{/if}
		                    {if !empty($pageId)}<input type="hidden" name="pageId" value="{$pageId|escape:"html"}">{/if}
		                    {if !empty($comment)}<input type="hidden" id="comment" name="comment" value="{$comment|escape:"html"}">{/if}
		                    {if !empty($cardNumber)}<input type="hidden" name="cardNumber" value="{$cardNumber|escape:"html"}">{/if}
		                    {if $ssoService == 'ldap'}<input type="hidden" name="ldapLogin" value="true">{/if}
		                </div>
		            </div>
		        </div>
		    </form>
        {/if}
	</div>
</div>
{/strip}
{literal}
<script type="text/javascript">
	$('#username').focus().select();
	$(function(){
		AspenDiscovery.Account.validateCookies();
		var hasLocalStorage = AspenDiscovery.hasLocalStorage() || false;
		if (hasLocalStorage) {
			var rememberMe = (window.localStorage.getItem('rememberMe') == 'true'); // localStorage saves everything as strings
			var showCovers = window.localStorage.getItem('showCovers') || false;
			if (rememberMe) {
				var lastUserName = window.localStorage.getItem('lastUserName');
				var lastPwd = window.localStorage.getItem('lastPwd');
				{/literal}{*// showPwd = (window.localStorage.getItem('showPwd') == 'true'); // localStorage saves everything as strings *}{literal}
				$("#username").val(lastUserName);
				$("#password").val(lastPwd);
				{/literal}{*// $("#showPwd").prop("checked", showPwd  ? "checked" : '');
//					if (showPwd) AspenDiscovery.pwdToText('password');*}{literal}
			}
			if(rememberMe || ({/literal}{$checkRememberMe}{literal} === 1)) {
				$("#rememberMe").prop("checked", true);
			} else {
				$("#rememberMe").prop("checked", '');
			}
			//$("#rememberMe").prop("checked", rememberMe ? "checked" : '');
			if (showCovers.length > 0) {
				$("<input>").attr({
					type: 'hidden',
					name: 'showCovers',
					value: showCovers
				}).appendTo('#loginForm');
			}
		} else {
			{/literal}{* // disable, uncheck & hide RememberMe checkbox if localStorage isn't available.*}{literal}
			$("#rememberMe").prop({checked : '', disabled: true}).parent().hide();
		}
		{/literal}{* // Once Box is shown, focus on username input and Select the text;
			$("#modalDialog").on('shown.bs.modal', function(){
				$('#username').focus().select();
			})*}{literal}
	});
</script>
{/literal}