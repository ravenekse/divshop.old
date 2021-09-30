<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = false;

/** Custom routes | Updates */
$route['panel/dashboard/update/notes'] = 'panel/dashboard/notesSave'; /** Dashboard notes */
$route['panel/settings/save'] = 'panel/settings/saveSettings'; /** Settings save */
$route['panel/paysettings/save'] = 'panel/paysettings/savePayments'; /** Payments save */
$route['panel/account/save'] = 'panel/account/saveAccount'; /** Settings save */

/** Custom routes | Add */
$route['panel/add/admin/create'] = 'panel/add/adminCreate';
$route['panel/add/server/create'] = 'panel/add/serverCreate';
$route['panel/add/service/create'] = 'panel/add/serviceCreate';
$route['panel/add/voucher/create'] = 'panel/add/voucherCreate';
$route['panel/add/news/create'] = 'panel/add/newsCreate';
$route['panel/add/page/create'] = 'panel/add/pageCreate';

/** Custom routes | Edit */
$route['panel/edit/admin/save'] = 'panel/edit/adminSave';
$route['panel/edit/server/save'] = 'panel/edit/serverSave';
$route['panel/edit/service/save'] = 'panel/edit/serviceSave';
$route['panel/edit/news/save'] = 'panel/edit/newsSave';
$route['panel/edit/page/save'] = 'panel/edit/pageSave';

/** Custom routes | Remove */
$route['panel/remove/admin'] = 'panel/remove/adminRemove';
$route['panel/remove/server'] = 'panel/remove/serverRemove';
$route['panel/remove/service'] = 'panel/remove/serviceRemove';
$route['panel/remove/news'] = 'panel/remove/newsRemove';
$route['panel/remove/page'] = 'panel/remove/pageRemove';
$route['panel/remove/voucher'] = 'panel/remove/voucherRemove';

/** Custom routes | Pagination */
$route['panel/admins/(:any)'] = 'panel/admins/index/$1'; /** Admins pagination */
$route['panel/servers/(:any)'] = 'panel/servers/index/$1'; /** Servers pagination */
$route['panel/services/(:any)'] = 'panel/services/index/$1'; /** Services pagination */
$route['panel/vouchers/(:any)'] = 'panel/vouchers/index/$1'; /** Vouchers pagination */
$route['panel/purchases/(:any)'] = 'panel/purchases/index/$1'; /** Purchases pagination */
$route['panel/news/(:any)'] = 'panel/news/index/$1'; /** News pagination */
$route['panel/pages/(:any)'] = 'panel/pages/index/$1'; /** Pages pagination */
$route['panel/logs/(:any)'] = 'panel/logs/index/$1'; /** Logs pagination */
$route['panel/failedlogins/(:any)'] = 'panel/failedlogins/index/$1'; /** Failedlogins pagination */
$route['stats/(:any)'] = 'stats/index/$1'; /** Stats pagination */

/** Custom routes | News, Shop, Services & Pages */
$route['news/(:num)-(:any)'] = 'news/index/$1/$2';
$route['shop/(:num)-(:any)'] = 'shop/showServerShop/$1/$2';
$route['shop/(:num)-(:any)/service/(:num)-(:any)'] = 'service/index/$1/$2/$3/$4';
$route['page/(:any)'] = 'page/index/$1';

/** Custom routes | Payments */
$route['payments/paypal/cancel'] = 'payments/paypalCancel';
$route['payments/paypal/success'] = 'payments/paypalSuccess';
$route['payments/paypal/ipn'] = 'payments/paypalIPN';
$route['payments/transfer/end/(:any)'] = 'payments/transferEnd/$1';
$route['payments/transfer/ipn'] = 'payments/transferIPN';

/** Custom routes | Aliases */
$route['panel'] = 'panel/dashboard/redirectToDashboard/';
$route['admin/auth/forgotpassword'] = 'admin/forgotpassword';
$route['antybot/verify'] = 'antybot/verifyUser';
$route['voucher/verify'] = 'payments/voucher';
