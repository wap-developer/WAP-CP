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

$route['cp-admin'] = 'authentication';
$route['cp-admin/auth/login'] = 'authentication/login';
$route['account/logout'] = 'authentication/logout_process';
$route['auth/login-process'] = 'authentication/login_process';
$route['cp-admin/dashboard'] = 'admin/dashboard';
$route['cp-admin/seo-management'] = 'admin/seo';
$route['cp-admin/profile'] = 'admin/profile';
$route['cp-admin/gallery'] = 'admin/gallery';
$route['cp-admin/role-management'] = 'admin/role';
$route['cp-admin/menu-management'] = 'admin/menu';
$route['cp-admin/menu-role/(:num)'] = 'admin/menu_role/$1';
$route['cp-admin/delete-role/(:num)'] = 'admin/delete_role/$1';
$route['cp-admin/delete-menu/(:num)'] = 'admin/delete_menu/$1';
$route['news'] = 'landing/news';
$route['news/page/2'] = 'landing/news_page/(:any)';
$route['group'] = 'landing/group';
$route['job'] = 'landing/job';
$route['achievement'] = 'landing/achievement';
$route['gallery'] = 'landing/gallery';
$route['organization'] = 'landing/organization';
$route['gallery/detail/(:any)'] = 'landing/gallery_detail/(:any)';


$route['default_controller'] = 'landing';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
