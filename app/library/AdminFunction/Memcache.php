<?php
class Memcache{
    const CACHE_ON = false ;// 0: khong dung qua cache, 1: dung qua cache
    const CACHE_TIME_TO_LIVE_5 = 300; //Time cache 5 phut
    const CACHE_TIME_TO_LIVE_15 = 900; //Time cache 15 phut
    const CACHE_TIME_TO_LIVE_30 = 1800; //Time cache 30 phut
    const CACHE_TIME_TO_LIVE_60 = 3600; //Time cache 60 phut

    const CACHE_TIME_TO_LIVE_ONE_DAY = 86400; //Time cache 1 ngay
    const CACHE_TIME_TO_LIVE_ONE_WEEK = 604800; //Time cache 1 tuan
    const CACHE_TIME_TO_LIVE_ONE_MONTH = 2419200; //Time cache 1 thang
    const CACHE_TIME_TO_LIVE_ONE_YEAR =  29030400; //Time cache 1 nam

    //user shop
    const CACHE_ALL_USER_SHOP = 'cache_all_user_shop';
    const CACHE_USER_SHOP_ID = 'cache_user_shop_id_';

    //danh mục
    const CACHE_ALL_CATEGORY    = 'cache_all_category';
    const CACHE_CATEGORY_ID    = 'cache_category_id';

    //sản phẩm
    const CACHE_PRODUCT_ID    = 'cache_product_id';

    //sản phẩm
    const CACHE_NEW_ID    = 'cache_news_id';
}
