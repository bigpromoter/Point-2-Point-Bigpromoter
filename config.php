<?php
define('P2P_TRANSLATE',     'p2p_bigpromoter');
define('P2P_DIR',           dirname(__FILE__).'/');
define('P2P_DIR_CORE',      P2P_DIR.'core/');
define('P2P_DIR_SYSTEM',    P2P_DIR.'system/');
define('P2P_DIR_CONTROL',   P2P_DIR_CORE.'control/control_');
define('P2P_DIR_MODEL',     P2P_DIR_CORE.'model/model_');
define('P2P_DIR_VIEW',      P2P_DIR_CORE.'view/');
define('P2P_DIR_VIEW_ADMIN',P2P_DIR_CORE.'view/admin/');
define('P2P_DIR_VIEW_USER', P2P_DIR_CORE.'view/user/');
define('P2P_DIR_INCLUDE',   P2P_DIR_CORE.'include/include_');

define('P2P_DIR_EMAIL_TEMPLATE',  P2P_DIR.'assets/template/email/default/mail_template_');

define('P2P_DIR_CALENDAR',  P2P_DIR.'assets/calendar/');
define('P2P_DIR_CALENDAR_KEY',  P2P_DIR.'assets/calendar/google/key/');
define('P2P_OFFSET_CALENDAR', -25200);

define('P2P_DIR_PAYMENT',  P2P_DIR.'assets/payment/');
define('P2P_DIR_PAYMENT_PAYPAL',  P2P_DIR.'assets/payment/PayPal/');
define('P2P_DIR_PAYMENT_BRAINTREE',  P2P_DIR.'assets/payment/braintree/');

define('P2P_TITLE',   '<img src="'.P2P_DIR_IMAGES.'logo.png"/>');

define('P2P_MAX_PASSENGER', 20);
define('P2P_MAX_LUGGAGE', 20);

define('DISTANCE_KM',1000);
define('DISTANCE_MILE',1609.344);
?>