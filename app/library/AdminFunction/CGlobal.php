<?php
/**
 * Created by JetBrains PhpStorm.
 * User: QuynhTM
 */
class CGlobal{
    static $css_ver = 1;
    static $js_ver = 1;
    public static $POS_HEAD = 1;
    public static $POS_END = 2;
    public static $extraHeaderCSS = '';
    public static $extraHeaderJS = '';
    public static $extraFooterCSS = '';
    public static $extraFooterJS = '';
    public static $pageAdminTitle = 'Dashboard Admin';
    public static $pageShopTitle = 'Shop Admin';

    const web_name = 'Website';
    const number_limit_show = 30;

    const status_show = 1;
    const status_hide = 0;
    const status_block = -2;

    //Tin tuc
    const NEW_CATEGORY_CUSTOMER = 1;
    const NEW_CATEGORY_SHOP = 2;
    const NEW_CATEGORY_GIOI_THIEU = 3;
    const NEW_CATEGORY_GIAI_TRI = 4;
    const NEW_CATEGORY_THI_TRUONG = 5;
    const NEW_CATEGORY_GOC_GIA_DINH = 6;
    const NEW_CATEGORY_TIN_TUC_CHUNG = 7;
    const NEW_CATEGORY_QUANG_CAO = 8;
    const NEW_TYPE_DAC_BIET = 1;// di voi danh muc: 1,2,3
    const NEW_TYPE_NOI_BAT = 2;// di voi danh muc: 4,5,6,7
    const NEW_TYPE_TIN_TUC = 3;// di voi danh muc: 4,5,6,7
    const NEW_TYPE_QUANG_CAO = 4;// di voi danh muc: 8
    public static $arrCategoryNew = array(-1 => '--- Chọn danh mục ---',
        self::NEW_CATEGORY_TIN_TUC_CHUNG => 'Tin tức chung',
        self::NEW_CATEGORY_GOC_GIA_DINH => 'Góc gia đinh',
        self::NEW_CATEGORY_THI_TRUONG => 'Thị trường',
        self::NEW_CATEGORY_GIAI_TRI => 'Giải trí',
        self::NEW_CATEGORY_GIOI_THIEU => 'Tin giới thiệu',
        self::NEW_CATEGORY_SHOP => 'Tin của Shop',
        self::NEW_CATEGORY_CUSTOMER => 'Tin của khách',
        self::NEW_CATEGORY_QUANG_CAO => 'Tin quảng cáo',
    );
    public static $arrTypeNew = array(-1 => '--- Chọn kiểu tin ---',
        self::NEW_TYPE_TIN_TUC => 'Tin tức chung',
        self::NEW_TYPE_NOI_BAT => 'Tin nổi bật',
        self::NEW_TYPE_DAC_BIET => 'Tin đặc biệt',
        self::NEW_TYPE_QUANG_CAO => 'Tin quảng cáo',
    );

    const IMAGE_ERROR = 133;
    const FOLDER_NEWS = 'news';
    const FOLDER_PRODUCT = 'product';

    //shop
    const SHOP_FREE = 1;
    const SHOP_NOMAL = 2;
    const SHOP_VIP = 3;
    const SHOP_ONLINE = 1;
    const SHOP_OFFLINE = 0;
    const SHOP_NUMBER_PRODUCT_FREE = 10;
    const SHOP_NUMBER_PRODUCT_NOMAL = 100;
    const SHOP_NUMBER_PRODUCT_VIP = 5000;

    //order
    const ORDER_STATUS_DELETE = 0;
    const ORDER_STATUS_NEW = 1;
    const ORDER_STATUS_CHECKED = 2;
    const ORDER_STATUS_SUCCESS = 3;
    const ORDER_STATUS_CANCEL = 4;


}