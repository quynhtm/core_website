<?php
/*
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
*/

return array(
        'driver' => 'smtp',
        'host' => 'mail.greechip.net',
        'port' => 465,
        'from' => array('address' => 'no-reply@shopcuatui.com.vn', 'name' => CGlobal::web_name),
        'encryption' => 'ssl',
        'username' => 'info@greechip.net',
        'password' => '12345678b@',
        'sendmail' => '/usr/sbin/sendmail -bs',
        'pretend' => false,
);
