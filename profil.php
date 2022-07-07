<html>
<head>
<title>Bewertungsprofil von <?php echo $_GET['username'] ?> bei Shopste.com</title>
<link rel="stylesheet" href="/framework/raty-2.7.0/lib/jquery.raty.css">
<script src="/js/jquery-1.9.0.js"></script>
<script src="/framework/raty-2.7.0/lib/jquery.raty.js"></script>
	
</head>
<body>
<h1>Bewertungen für Mitglied <?php echo $_GET['username'] ?></h1>

	<div id="raty-benutzer"></div>

			click: function(score, evt) {
			alert('ID: ' + this.id + "\nscore: " + score + "\nevent: " + evt);
		}
<script>
	$.fn.raty.defaults.path = '/framework/raty-2.7.0/demo/images/';
	//$('#raty-benutzer').raty({ half: true, hints       : ['magenhalft', 'ausreichend', 'befriediegend', 'gut', 'sehr gut']});
	$('#raty-benutzer').raty({ 
	half: true, hints       : ['magenhalft', 'ausreichend', 'befriediegend', 'gut', 'sehr gut'],
	click: function(score, evt) {
			alert('ID: ' + this.id + "\nscore: " + score + "\nevent: " + evt);
	} 
	});
	
	</script>
		
	.raty() ({
		cancel   : true,
		half     : true,
		,
		score    : 3.26
	});
</body>