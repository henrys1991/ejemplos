<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="Cache-Control" Content="no-cache">
		<meta http-equiv="Pragma" Content="no-cache">
		<meta http-equiv="Expires" Content="0">
		<title><?=$title?></title>
		<?php
			require_js_constants(array('BASE_URL'));
			require_js_framework(array('prototype', 'scriptaculous'));
			require_js_library('Pagination');
			require_js_helper($helpers);
			require_css('si_apps');
			require_css($css);
			require_js_library($libraries);
			
			echo (!empty($jscalendar)) ? require_js_calendar() : '';
			echo (!empty($jsdate)) ? require_js_date() : '';
		?>
	</head>
	<body class="app">