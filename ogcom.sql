<!DOCTYPE html>
<html lang="en" dir="ltr">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="robots" content="noindex">
<meta name="referrer" content="origin-when-crossorigin">
<title>Login - Adminer</title>
<link rel="stylesheet" type="text/css" href="?file=default.css&amp;version=4.3.1&amp;driver=mysql">
<script type="text/javascript" src="?file=functions.js&amp;version=4.3.1&amp;driver=mysql"></script>
<link rel="shortcut icon" type="image/x-icon" href="?file=favicon.ico&amp;version=4.3.1&amp;driver=mysql">
<link rel="apple-touch-icon" href="?file=favicon.ico&amp;version=4.3.1&amp;driver=mysql">
<link rel="stylesheet" type="text/css" href="adminer.css">

<body class="ltr nojs" onkeydown="bodyKeydown(event);" onclick="bodyClick(event);">
<script type="text/javascript">
document.body.className = document.body.className.replace(/ nojs/, ' js');
var offlineMessage = 'You are offline.';
</script>

<div id="help" class="jush-sql jsonly hidden" onmouseover="helpOpen = 1;" onmouseout="helpMouseout(this, event);"></div>

<div id="content">
<h2>Login</h2>
<div id='ajaxstatus' class='jsonly hidden'></div>
<div class='error'>Access denied for user &#039;root&#039;@&#039;localhost&#039; (using password: NO)<br>Master password expired. <a href="https://www.adminer.org/en/extension/" target="_blank">Implement</a> <code>permanentLogin()</code> method to make it permanent.</div>
<form action='' method='post'>
<table cellspacing="0">
<tr><th>System<td><input type='hidden' name='auth[driver]' value='server'>MySQL
<tr><th>Server<td><input name="auth[server]" value="" title="hostname[:port]" placeholder="localhost" autocapitalize="off">
<tr><th>Username<td><input name="auth[username]" id="username" value="root" autocapitalize="off">
<tr><th>Password<td><input type="password" name="auth[password]">
<tr><th>Database<td><input name="auth[db]" value="ogcom" autocapitalize="off">
</table>
<script type="text/javascript">
focus(document.getElementById('username'));
</script>
<p><input type='submit' value='Login'>
<label><input type='checkbox' name='auth[permanent]' value='1'>Permanent login</label>
<div></div>
</form>
</div>

<div id="menu">
<h1>
<a href='https://www.adminer.org/' target='_blank' id='h1'>Adminer</a> <span class="version">4.3.1</span>
<a href="https://www.adminer.org/#download" target="_blank" id="version"></a>
</h1>
</div>
<script type="text/javascript">setupSubmitHighlight(document);</script>
