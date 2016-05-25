<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
| 	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved 
| routes must come before any wildcard or regular expression routes.
|
*/

$route['default_controller'] = "site";
$route['404_override'] = '';

/*
*	Site Routes
*/
$route['home'] = 'site/home_page';
$route['about'] = 'site/about';
$route['services'] = 'site/services';
$route['contact'] = 'site/contact';
$route['login'] = 'member/auth/login_member';
$route['logout'] = 'member/auth/logout_member';

/*
*	Blog Routes
*/
$route['blog'] = 'site/blog';
$route['blog/(:num)'] = 'site/blog/index/__/__/$1';//going to different page without any filters
$route['blog/(:any)'] = 'site/blog/view_post/$1';//going to single post page
$route['blog/category/(:any)'] = 'site/blog/index/$1';//category present
$route['blog/category/(:any)/(:num)'] = 'site/blog/index/$1/$2';//category present going to next page
$route['blog/search/(:any)'] = 'site/blog/index/__/$1';//search present
$route['blog/search/(:any)/(:num)'] = 'site/blog/index/__/$1/$2';//search present going to next page

/*
*	Login Routes
*/
$route['login-admin'] = 'admin/auth/login_admin';
$route['logout-admin'] = 'admin/auth/logout_admin';

/*
*	Settings Routes
*/
$route['settings'] = 'admin/settings';
$route['dashboard'] = 'admin/dashboard';

/*
*	Users Routes
*/
$route['admin/administrators'] = 'admin/users';
$route['admin/administrators/(:any)/(:any)/(:num)'] = 'admin/users/index/$1/$2/$3';
$route['add-user'] = 'admin/users/add_user';
$route['edit-user/(:num)'] = 'admin/users/edit_user/$1';
$route['delete-user/(:num)'] = 'admin/users/delete_user/$1';
$route['activate-user/(:num)'] = 'admin/users/activate_user/$1';
$route['deactivate-user/(:num)'] = 'admin/users/deactivate_user/$1';
$route['reset-user-password/(:num)'] = 'admin/users/reset_password/$1';
$route['admin-profile/(:num)'] = 'admin/users/admin_profile/$1';

/*
*	Customers Routes
*/
$route['view-invoice/(:num)'] = 'admin/customers/view_invoice/$1';
$route['all-members'] = 'admin/members';
$route['all-members/(:num)'] = 'admin/members/index/$1';
$route['delete-member/(:num)'] = 'admin/members/delete_member/$1';
$route['activate-member/(:num)'] = 'admin/members/activate_member/$1';
$route['deactivate-member/(:num)'] = 'admin/members/deactivate_member/$1';

/*
*	Member Routes
*/

$route['register'] = 'member/auth/register_member';
$route['login'] = 'member/auth/login_member';
$route['logout'] = 'member/sign_out';
$route['account'] = 'member/my_account';
$route['uploads'] = 'member/uploads';

/*
*	Accounts Routes
*/
$route['admin/accounts-receivable'] = 'admin/accounts/accounts_receivable';
$route['admin/accounts-receivable/(:num)'] = 'admin/accounts/accounts_receivable/$1';
$route['admin/accounts-receivable/(:any)/(:any)/(:num)'] = 'admin/accounts/accounts_receivable/$1/$2/$3';
$route['admin/accounts-payable'] = 'admin/accounts/accounts_payable';
$route['admin/accounts-payable/(:num)'] = 'admin/accounts/accounts_payable/$1';
$route['admin/accounts-payable/(:any)/(:any)/(:num)'] = 'admin/accounts/accounts_payable/$1/$2/$3';
$route['admin/confirm-payment/(:num)/(:any)/(:any)/(:any)/(:any)'] = 'admin/accounts/confirm_payment/$1/$2/$3/$4/$5';
$route['admin/unconfirm-payment/(:num)/(:any)/(:any)/(:any)/(:any)'] = 'admin/accounts/unconfirm_payment/$1/$2/$3/$4/$5';
$route['admin/receipt-payment/(:num)/(:any)/(:any)/(:any)/(:any)'] = 'admin/accounts/receipt_payment/$1/$2/$3/$4/$5';
$route['admin/search-accounts-receivable'] = 'admin/accounts/search_accounts_receivable';
$route['admin/close-receivable-search'] = 'admin/accounts/close_accounts_receivable_search';
$route['admin/search-accounts-payable'] = 'admin/accounts/search_accounts_payable';
$route['admin/close-payable-search'] = 'admin/accounts/close_accounts_payable_search';
$route['admin/receipts'] = 'admin/accounts/receipts';
$route['admin/receipts/(:num)'] = 'admin/accounts/receipts/$1';
$route['admin/receipts/(:any)/(:any)/(:num)'] = 'admin/accounts/receipts/$1/$2/$3';
$route['admin/search-receipts'] = 'admin/accounts/search_receipts';
$route['admin/close-payable-search'] = 'admin/accounts/close_receipts_search';


//sections
$route['administration/sections'] = 'admin/sections/index';
$route['administration/sections/(:any)/(:any)/(:num)'] = 'admin/sections/index/$1/$2/$3';
$route['administration/add-section'] = 'admin/sections/add_section';
$route['administration/edit-section/(:num)'] = 'admin/sections/edit_section/$1';

$route['administration/edit-section/(:num)/(:num)'] = 'admin/sections/edit_section/$1/$2';
$route['administration/delete-section/(:num)'] = 'admin/sections/delete_section/$1';
$route['administration/delete-section/(:num)/(:num)'] = 'admin/sections/delete_section/$1/$2';
$route['administration/activate-section/(:num)'] = 'admin/sections/activate_section/$1';
$route['administration/activate-section/(:num)/(:num)'] = 'admin/sections/activate_section/$1/$2';
$route['administration/deactivate-section/(:num)'] = 'admin/sections/deactivate_section/$1';
$route['administration/deactivate-section/(:num)/(:num)'] = 'admin/sections/deactivate_section/$1/$2';

//add members
$route['members/add-member'] = 'admin/members/add_member';
//imort of members
$route['members'] = 'admin/members/index';
$route['members/validate-import'] = 'admin/members/do_members_import';
$route['import/members-template'] = 'admin/members/import_members_template';
$route['members/import-members'] = 'admin/members/import_members';

//contact 
$route['administration/contacts']='admin/contacts/show_contacts';
$route['admin/company-profile'] = 'admin/contacts/show_contacts';

//about us routes
$route['front-page-about'] = 'admin/blog/front_post/32';

//company services
$route['company-services'] = 'admin/services/index';
$route['administration/all-services'] = 'admin/services/index';
$route['administration/all-services/(:num)'] = 'admin/services/index/$1';//with a page number
$route['administration/add-service'] = 'admin/services/add_service';
$route['administration/edit-service/(:num)/(:num)'] = 'admin/services/edit_service/$1/$2';
$route['administration/activate-service/(:num)/(:num)'] = 'admin/services/activate_service/$1/$2';
$route['administration/deactivate-service/(:num)/(:num)'] = 'admin/services/deactivate_service/$1/$2';
$route['administration/delete-service/(:num)/(:num)'] = 'admin/services/delete_service/$1/$2';

//company routes
$route['company-gallery'] = 'admin/gallery';
$route['administration/all-gallery-images'] = 'admin/gallery/index';
$route['administration/all-gallery-images/(:num)'] = 'admin/gallery/index/$1';//with a page number
$route['administration/add-gallery'] = 'admin/gallery/add_gallery';
$route['administration/edit-gallery/(:num)/(:num)'] = 'admin/gallery/edit_gallery/$1/$2';
$route['administration/activate-gallery/(:num)/(:num)'] = 'admin/gallery/activate_gallery/$1/$2';
$route['administration/deactivate-gallery/(:num)/(:num)'] = 'admin/gallery/deactivate_gallery/$1/$2';
$route['administration/delete-gallery/(:num)/(:num)'] = 'admin/gallery/delete_gallery/$1/$2';

//blog routes
$route['posts'] = 'admin/blog';
$route['blog/posts'] = 'admin/blog';
$route['blog/categories'] = 'admin/blog/categories';
$route['add-post'] = 'admin/blog/add_post';
$route['edit-post/(:num)'] = 'admin/blog/edit_post/$1';
$route['delete-post/(:num)'] = 'admin/blog/delete_post/$1';
$route['activate-post/(:num)'] = 'admin/blog/activate_post/$1';
$route['deactivate-post/(:num)'] = 'admin/blog/deactivate_post/$1';
$route['post-comments/(:num)'] = 'admin/blog/post_comments/$1';
$route['blog/comments/(:num)'] = 'admin/blog/comments/$1';
$route['blog/comments'] = 'admin/blog/comments';
$route['add-comment/(:num)'] = 'admin/blog/add_comment/$1';
$route['delete-comment/(:num)/(:num)'] = 'admin/blog/delete_comment/$1/$2';
$route['activate-comment/(:num)/(:num)'] = 'admin/blog/activate_comment/$1/$2';
$route['deactivate-comment/(:num)/(:num)'] = 'admin/blog/deactivate_comment/$1/$2';
$route['add-blog-category'] = 'admin/blog/add_blog_category';
$route['edit-blog-category/(:num)'] = 'admin/blog/edit_blog_category/$1';
$route['delete-blog-category/(:num)'] = 'admin/blog/delete_blog_category/$1';
$route['activate-blog-category/(:num)'] = 'admin/blog/activate_blog_category/$1';
$route['deactivate-blog-category/(:num)'] = 'admin/blog/deactivate_blog_category/$1';
$route['delete-comment/(:num)'] = 'admin/blog/delete_comment/$1';
$route['activate-comment/(:num)'] = 'admin/blog/activate_comment/$1';
$route['deactivate-comment/(:num)'] = 'admin/blog/deactivate_comment/$1';

//projects
$route['projects'] = 'site/projects';
/* End of file routes.php */
/* Location: ./system/application/config/routes.php */


//trainings
$route['trainings'] = 'admin/trainings/index';
$route['trainings/(:num)'] = 'admin/trainings/index/$1';//with a page number
$route['administration/add-training'] = 'admin/trainings/add_training';
$route['administration/edit-training/(:num)/(:num)'] = 'admin/trainings/edit_training/$1/$2';
$route['administration/activate-training/(:num)/(:num)'] = 'admin/trainings/activate_training/$1/$2';
$route['administration/deactivate-training/(:num)/(:num)'] = 'admin/trainings/deactivate_training/$1/$2';
$route['administration/delete-training/(:num)/(:num)'] = 'admin/trainings/delete_training/$1/$2';

$route['slideshow'] = 'admin/slideshow/index';
$route['slideshow/(:num)'] = 'admin/slideshow/index/$1';
$route['administration/all-slides/(:num)'] = 'admin/slideshow/index/$1';//with a page number
$route['administration/add-slide'] = 'admin/slideshow/add_slide';
$route['administration/edit-slide/(:num)/(:num)'] = 'admin/slideshow/edit_slide/$1/$2';
$route['administration/activate-slide/(:num)/(:num)'] = 'admin/slideshow/activate_slide/$1/$2';
$route['administration/deactivate-slide/(:num)/(:num)'] = 'admin/slideshow/deactivate_slide/$1/$2';
$route['administration/delete-slide/(:num)/(:num)'] = 'admin/slideshow/delete_slide/$1/$2';