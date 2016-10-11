<?php

return array(
	'driver' => 'smtp',
	'host' => 'linux203.gnet.vn',
	'port' => 465,
	'from' => array('address' => 'mail@shopcuatui.com.vn', 'name' => CGlobal::web_name),
	'encryption' => 'ssl',
	'username' => 'mail@shopcuatui.com.vn',
	'password' => '~S;&aWW*ux9H',
	'sendmail' => '/usr/sbin/sendmail -bs',
	'pretend' => false,
);

/*
return array(
	'driver' => 'smtp',
	'host' => 'linux.greechip.net',
	'port' => 587,
	'from' => array('address' => 'no-reply@shopcuatui.com.vn', 'name' => CGlobal::web_name),
	'encryption' => 'ssl',
	'username' => 'no-reply@shopcuatui.com.vn',
	'password' => 'vKe#521x',
	'sendmail' => '/usr/sbin/sendmail -bs',
	'pretend' => false,
);
*/