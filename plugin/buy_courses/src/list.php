<?php
/**
 * @package chamilo.plugin.buy_courses
 */
/**
 * Initialization
 */

require_once '../../../main/inc/global.inc.php';
require_once api_get_path(LIBRARY_PATH) . 'plugin.class.php';
require_once 'buy_course_plugin.class.php';
require_once 'buy_course.lib.php';

$course_plugin = 'buy_courses';
$plugin = Buy_CoursesPlugin::create();
$_cid = 0;
$teacher = api_is_platform_admin();

if ($teacher) {
    $interbreadcrumb[] = array("url" => "configuration.php", "name" => $plugin->get_lang('AvailableCoursesConfiguration'));
    $interbreadcrumb[] = array("url" => "paymentsetup.php", "name" => $plugin->get_lang('PaymentsConfiguration'));
}

$tpl = new Template('CourseListOnSale');
if (isset($_SESSION['bc_success'])) {
    $tpl->assign('rmessage', 'YES');
    if ($_SESSION['bc_success'] == true) {
        $message = sprintf(utf8_encode($plugin->get_lang($_SESSION['bc_message'])), $_SESSION['bc_url']);
        unset($_SESSION['bc_url']);
        $tpl->assign('estilo', 'confirmation-message');
    } else {
        $message = utf8_encode($plugin->get_lang($_SESSION['bc_message']));
        $tpl->assign('estilo', 'warning-message');
    }
    $tpl->assign('mensaje', $message);
    unset($_SESSION['bc_success']);
    unset($_SESSION['bc_message']);

} else {
    $tpl->assign('rmessage', 'NO');
}

$courseList = userCourseList();
$categoryList = listCategories();
$currencyType = findCurrency();

$tpl->assign('server', $_configuration['root_web']);
$tpl->assign('cursos', $courseList);
$tpl->assign('categorias', $categoryList);
$tpl->assign('currency', $currencyType);

$listing_tpl = 'buy_courses/view/list.tpl';
$content = $tpl->fetch($listing_tpl);
$tpl->assign('content', $content);
$tpl->display_one_col_template();
