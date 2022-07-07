
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		<meta content="INDEX,FOLLOW" name="robots">
		<link media="all" href="../css/template_master.css" type="text/css" rel="stylesheet">
		<title>{$CORE_PLATTFORMNAME} Login Administrationsbereich</title>
	</head>
	<body>
	
	
	{if $logged_in == true}
		
			<div class="page">
			<div class="block block-cart">
				<div class="block-title"> <h1>{$CORE_PLATTFORMNAME} Administrationsbereich</h1></div>
				<div class="content">
					<p>
					<h2>Login in den {$CORE_PLATTFORMNAME} Administrationsbereich</h2>
			
					{$strMessage}
				
					<form action="/ACP/login.php" method="POST">
							<div style="margin-bottom:7px">Anmelde Domain:&nbsp;{$domain_name}<br/></div>
							<div style="margin-bottom:7px;">Benutzername:<input type="text" style="margin-left:5px" name="txtUsername" value="{$admin_user}"/><br/></div>
							<div style="margin-bottom:7px">Passwort:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="password" name="txtPasswort"/><br/><br/></div>
							<label><input type="checkbox" value="Y" name="chkEingeloggtbleiben"/>Dauerhaft angemeldet bleiben (setzt Cookie)</label><br/><br/>
							<input type="submit" class="button" value="Anmeldung"/>
					</form>
					</p>
				</div>
				</div>
			</div>
	{else}
		<div class="page">
			<div class="block block-cart">
				<div class="block-title"> <h1>Login in {$CORE_PLATTFORMNAME} Administrationsbreich</h1></div>
					<div class="content"><p>
						<h2>Login {$CORE_PLATTFORMNAME} Administrationsbereich</h2>
						{$strMessage}
						Sie sind bereits als {$admin_user} angemeldet.<br/><br/>
				
						<a href="/index.php?modus=logout">Abmelden</a><br/>
						<a href="/">Zur Startseite der Domain gehen</a>
						</p>
					</div>
				</div>
		</div>		
	{/if}
	</body>
</html>