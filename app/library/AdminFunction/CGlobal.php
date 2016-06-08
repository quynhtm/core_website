<?php
/**
 * Created by JetBrains PhpStorm.
 * User: QuynhTM
 */
class CGlobal{

    const web_name = 'Website';
    const number_limit_show = 30;

    const status_show = 1;
    const status_hide = 0;

    static $css_ver = 1;
    static $js_ver = 1;
    public static $POS_HEAD = 1;
    public static $POS_END = 2;
    public static $extraHeaderCSS = '';
    public static $extraHeaderJS = '';
    public static $extraFooterCSS = '';
    public static $extraFooterJS = '';

    public static $pageAdminTitle = 'Dashboard Admin';

    public static $aryCatergoryNews = array(
        'tin-tuc-chung' => 'Tin tức chung',
        'goc-gia-dinh' => 'Góc gia đinh',
        'thi-truong' => 'Thị trường',
        'giai-tri' => 'Giải trí',
        'gioi-thieu' => 'Tin giới thiệu',
        'tin-cua-shop' => 'Tin của Shop',
        'tin-cua-khach' => 'Tin của khách',
        'tin-quang-cao' => 'Tin quảng cáo',
    );

}