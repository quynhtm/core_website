<?php

/*return array(

	'driver' => 'smtp',
	'host' => 'smtp.mailgun.org',
	'port' => 587,
	'from' => array('address' => null, 'name' => null),
	'encryption' => 'tls',
	'username' => null,
	'password' => null,
	'sendmail' => '/usr/sbin/sendmail -bs',
	'pretend' => false,
);*/

// return array(
// 	'driver' => 'smtp',
// 	'host' => 'smtp.gmail.com',
// 	'port' => 587,
// 	'from' => array('address' => 'shoponlinecuatui@gmail.com', 'name' => 'Awesome Laravel 4 Auth App'),
// 	'encryption' => 'tls',
// 	'username' => 'shoponlinecuatui@gmail.com',
// 	'password' => 'shoponlinecuatui@133',
// 	'sendmail' => '/usr/sbin/sendmail -bs',
// 	'pretend' => false,
// );

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