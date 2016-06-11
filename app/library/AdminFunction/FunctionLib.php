<?php
/**
 * Created by PhpStorm.
 * User: MT969
 * Date: 6/5/2015
 * Time: 5:01 PM
 */

class FunctionLib {

    /**
     * @param $file_name
     */
    static function link_css($file_name, $position = 1) {
        if (is_array($file_name)) {
            foreach ($file_name as $v) {
                self::link_css($v);
            }
            return;
        }
        if (strpos($file_name, 'http://') !== false) {
            $html = '<link rel="stylesheet" href="' . $file_name . ((CGlobal::$css_ver) ? '?ver=' . CGlobal::$css_ver : '') . '" type="text/css">' . "\n";
            if ($position == CGlobal::$POS_HEAD && strpos(CGlobal::$extraHeaderCSS, $html) === false)
                CGlobal::$extraHeaderCSS .= $html . "\n";
            elseif ($position == CGlobal::$POS_END && strpos(CGlobal::$extraFooterCSS, $html) === false)
                CGlobal::$extraFooterCSS .= $html . "\n";
            return;
        } else {
            $html = '<link type="text/css" rel="stylesheet" href="' .Config::get('config.WEB_ROOT') . 'assets/' . $file_name . ((CGlobal::$css_ver) ? '?ver=' . CGlobal::$css_ver : '') . '" />' . "\n";
            if ($position == CGlobal::$POS_HEAD && strpos(CGlobal::$extraHeaderCSS, $html) === false)
                CGlobal::$extraHeaderCSS .= $html . "\n";
            elseif ($position == CGlobal::$POS_END && strpos(CGlobal::$extraFooterCSS, $html) === false)
                CGlobal::$extraFooterCSS .= $html . "\n";
        }
    }

    /**
     * @param $file_name
     */
    static function link_js($file_name, $position = 1) {
        if (is_array($file_name)) {
            foreach ($file_name as $v) {
                self::link_js($v);
            }
            return;
        }
        if (strpos($file_name, 'http://') !== false) {
            $html = '<script type="text/javascript" src="' . $file_name . ((CGlobal::$js_ver) ? '?ver=' . CGlobal::$js_ver : '') . '"></script>';
            if ($position == CGlobal::$POS_HEAD && strpos(CGlobal::$extraHeaderJS, $html) === false)
                CGlobal::$extraHeaderJS .= $html . "\n";
            elseif ($position == CGlobal::$POS_END && strpos(CGlobal::$extraFooterJS, $html) === false)
                CGlobal::$extraFooterJS .= $html . "\n";
            return;
        } else {
            $html = '<script type="text/javascript" src="' . Config::get('config.WEB_ROOT')  . 'assets/' . $file_name . ((CGlobal::$js_ver) ? '?ver=' . CGlobal::$js_ver : '') . '"></script>';
            if ($position == CGlobal::$POS_HEAD && strpos(CGlobal::$extraHeaderJS, $html) === false)
                CGlobal::$extraHeaderJS .= $html . "\n";
            elseif ($position == CGlobal::$POS_END && strpos(CGlobal::$extraFooterJS, $html) === false)
                CGlobal::$extraFooterJS .= $html . "\n";
        }
    }

    static function site_css($file_name, $position = 1) {
        if (is_array($file_name)) {
            foreach ($file_name as $v) {
                self::site_css($v);
            }
            return;
        }
        if (strpos($file_name, 'http://') !== false) {
            $html = '<link rel="stylesheet" href="' . $file_name . ((CGlobal::$css_ver) ? '?ver=' . CGlobal::$css_ver : '') . '" type="text/css">' . "\n";
            if ($position == CGlobal::$POS_HEAD && strpos(CGlobal::$extraHeaderCSS, $html) === false)
                CGlobal::$extraHeaderCSS .= $html . "\n";
            elseif ($position == CGlobal::$POS_END && strpos(CGlobal::$extraFooterCSS, $html) === false)
                CGlobal::$extraFooterCSS .= $html . "\n";
            return;
        } else {
            $html = '<link type="text/css" rel="stylesheet" href="' . url('', array(), Config::get('config.SECURE')) . 'assets/' . $file_name . ((CGlobal::$css_ver) ? '?ver=' . CGlobal::$css_ver : '') . '" />' . "\n";
            if ($position == CGlobal::$POS_HEAD && strpos(CGlobal::$extraHeaderCSS, $html) === false)
                CGlobal::$extraHeaderCSS .= $html . "\n";
            elseif ($position == CGlobal::$POS_END && strpos(CGlobal::$extraFooterCSS, $html) === false)
                CGlobal::$extraFooterCSS .= $html . "\n";
        }
    }

    /**
     * @param $file_name
     */
    static function site_js($file_name, $position = 1) {
        if (is_array($file_name)) {
            foreach ($file_name as $v) {
                self::link_js($v);
            }
            return;
        }
        if (strpos($file_name, 'http://') !== false) {
            $html = '<script type="text/javascript" src="' . $file_name . ((CGlobal::$js_ver) ? '?ver=' . CGlobal::$js_ver : '') . '"></script>';
            if ($position == CGlobal::$POS_HEAD && strpos(CGlobal::$extraHeaderJS, $html) === false)
                CGlobal::$extraHeaderJS .= $html . "\n";
            elseif ($position == CGlobal::$POS_END && strpos(CGlobal::$extraFooterJS, $html) === false)
                CGlobal::$extraFooterJS .= $html . "\n";
            return;
        } else {
            $html = '<script type="text/javascript" src="' . url('', array(), Config::get('config.SECURE')) . 'assets/' . $file_name . ((CGlobal::$js_ver) ? '?ver=' . CGlobal::$js_ver : '') . '"></script>';
            if ($position == CGlobal::$POS_HEAD && strpos(CGlobal::$extraHeaderJS, $html) === false)
                CGlobal::$extraHeaderJS .= $html . "\n";
            elseif ($position == CGlobal::$POS_END && strpos(CGlobal::$extraFooterJS, $html) === false)
                CGlobal::$extraFooterJS .= $html . "\n";
        }
    }

    public static $array_allow_image = array(
        'jpg',
        'png',
        'jpeg'
    );

    public static $size_image_max = 1048576;

    public  static function numberToWord($s, $lang = 'vi') {
        $ds = 0;
        $so = $hang = array();

        $viN = array("không", "một", "hai", "ba", "bốn", "năm", "sáu", "bảy", "tám", "chín");
        $viRow = array("", "nghìn", "triệu", "tỷ");

        $enN = array("zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine");
        $enRow = array("", "thousand", "million", "billion");

        if ($lang == 'vi') {
            $so = $viN;
            $hang = $viRow;
        } else {
            $so = $enN;
            $hang = $enRow;
        }

        $s = str_replace(",", "", $s);
        $ds = (int) $s;
        if ($ds == 0) {
            return "không ";
        }

        $i = $j = $donvi = $chuc = $tram = 0;
        $i = strlen($s);

        $Str = "";
        if ($i == 0)
            $Str = "";
        else {
            $j = 0;
            while ($i > 0) {
                $donvi = substr($s, $i - 1, 1);
                $i = $i - 1;
                if ($i > 0) {
                    $chuc = substr($s, $i - 1, 1);
                } else {
                    $chuc = -1;
                }
                $i = $i - 1;
                if ($i > 0) {
                    $tram = substr($s, $i - 1, 1);
                } else {
                    $tram = -1;
                }
                $i = $i - 1;
                if ($donvi > 0 || $chuc > 0 || $tram > 0 || $j == 3)
                    $Str = $hang[$j] . " " . $Str;
                $j = $j + 1;
                if ($j > 3)
                    $j = 1;
                if ($donvi == 1 && $chuc > 1)
                    $Str = "mốt" . " " . $Str;
                else {
                    if ($donvi == 5 && $chuc > 0)
                        $Str = "lăm" . " " . $Str;
                    else if ($donvi > 0)
                        $Str = $so[$donvi] . " " . $Str;
                }
                if ($chuc < 0)
                    break;
                else
                    if ($chuc == 0 && $donvi > 0)
                        $Str = "lẻ" . " " . $Str;
                if ($chuc == 1)
                    $Str = "mười" . " " . $Str;
                if ($chuc > 1)
                    $Str = $so[$chuc] . " " . "mươi" . " " . $Str;
                if ($tram < 0)
                    break;
                else
                    if ($tram > 0 || $chuc > 0 || $donvi > 0)
                        $Str = $so[$tram] . " " . "trăm" . " " . $Str;
            }
        }
        return strtoupper(substr($Str, 0, 1)) . substr($Str, 1, strlen($Str) - 1) . ($lang == 'vi' ? "đồng" : 'vnd');
    }
    static function debug($array) {
        if($_SERVER['HTTP_HOST'] != 'shopcuatui.vn') {
            echo '<pre>';
            print_r($array);
            die;
        }
    }
    /**
     * build html select option
     *
     * @param array $options_array
     * @param int $selected
     * @param array $disabled
     */
    static function getOption($options_array, $selected, $disabled = array()) {
        $input = '';
        if ($options_array)
            foreach ($options_array as $key => $text) {
                $input .= '<option value="' . $key . '"';
                if (!in_array($selected, $disabled)) {
                    if ($key === '' && $selected === '') {
                        $input .= ' selected';
                    } else
                        if ($selected !== '' && $key == $selected) {
                            $input .= ' selected';
                        }
                }
                if (!empty($disabled)) {
                    if (in_array($key, $disabled)) {
                        $input .= ' disabled';
                    }
                }
                $input .= '>' . $text . '</option>';
            }
        return $input;
    }

    /**
     * build html select option mutil
     *
     * @param array $options_array
     * @param array $arrSelected
     */
    static function getOptionMultil($options_array, $arrSelected) {
        $input = '';
        if ($options_array)
            foreach ($options_array as $key => $text) {
                $input .= '<option value="' . $key . '"';
                if ($key === '' && empty($arrSelected)) {
                    $input .= ' selected';
                } else
                    if (!empty($arrSelected) && in_array($key, $arrSelected)) {
                        $input .= ' selected';
                    }
                $input .= '>' . $text . '</option>';
            }
        return $input;
    }
}