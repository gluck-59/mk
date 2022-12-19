<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty truncate modifier plugin
 *
 * Type:     modifier<br>
 * Name:     truncate<br>
 * Purpose:  Truncate a string to a certain length if necessary,
 *           optionally splitting in the middle of a word, and
 *           appending the $etc string or inserting $etc into the middle.
 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php
 *          truncate (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param integer
 * @param string
 * @param boolean
 * @param boolean
 * @return string
 */
 
function smarty_modifier_truncate1($string, $length = 80, $etc = '...', 
                                  $break_words = true, $middle = true) 
{ 
    if ($length == 0) 
        return ''; 

    if (mb_strlen($string, utf8) > $length) { 
        $length -= mb_strlen($etc, utf8); 
        if (!$break_words && !$middle) { 
            $string = mb_ereg_replace('/\s+?(\S+)?$/', '', mb_substr($string, 0, $length+1, utf8), utf8); 
        } 
        if(!$middle) { 
            return mb_substr($string, 0, $length, utf8).$etc; 
        } else { 
            return mb_substr($string, 0, $length/2, utf8) . $etc . mb_substr($string, -$length/2, utf8); 
        } 
    } else { 
        return $string; 
    } 
} 
 
 
function smarty_modifier_truncate($string, $length = 80, $etc = '...',
                                  $break_words = false, $middle = false)
{
    if ($length == 0)
        return '';

    if (mb_strlen($string, utf8) > $length) {
        $length -= min($length, mb_strlen($etc, utf8));
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', mb_substr($string, 0, $length+1, utf8));
        }
        if($middle === true) {
            //return substr($string, 0, $length) . $etc;
            return utf8_str_limit($string, $length, $etc);
        } else {
            return mb_substr($string, 0, $length/2, utf8) . $etc . mb_substr($string, -$length/2, utf8);
        }
    } else {
        return $string;
    }
}


/**
 * Обрезает текст в кодировке UTF-8 до заданной длины,
 * причём последнее слово показывается целиком, а не обрывается на середине.
 * Html сущности корректно обрабатываются.
 *
 * @param    string   $s           текст в кодировке UTF-8
 * @param    int      $maxlength   ограничение длины текста
 * @param    string   $continue    завершающая строка, которая будет вставлена после текста, если он обрежется
 * @param    string   &$is_cutted  текст был обрезан?
 * @return   string
 *
 * @license  http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @author   Nasibullin Rinat <n a s i b u l l i n  at starlink ru>
 * @charset  ANSI
 * @version  3.3.3
 */
function utf8_str_limit($s, $maxlength = 256, $continue = "\xe2\x80\xa6", &$is_cutted = null) #"\xe2\x80\xa6" = "&hellip;"
{
    $is_cutted = false;
    if ($continue === null) $continue = "\xe2\x80\xa6";

    #оптимизация скорости:
    #{{{
    if (mb_strlen($s) <= $maxlength) return $s;
    $s2 = str_replace("\r\n", '?', $s);
    $s2 = preg_replace('/&(?> [a-zA-Z][a-zA-Z\d]+
                            | \#(?> \d{1,4}
                                  | x[\da-fA-F]{2,4}
                                )
                          );  # html сущности (&lt; &gt; &amp; &quot;)
                        /sx', '?', $s2);
    #utf8_decode() converts characters that are not in ISO-8859-1 to '?', which, for the purpose of counting, is quite alright.
    if (mb_strlen($s2) <= $maxlength || mb_strlen(utf8_decode($s2)) <= $maxlength) return $s;
    #}}}

    preg_match_all('/(?> \r\n   # переносы строк
                       | &(?> [a-zA-Z][a-zA-Z\d]+
                            | \#(?> \d{1,4}
                                  | x[\da-fA-F]{2,4}
                                )
                          );  # html сущности (&lt; &gt; &amp; &quot;)
                       | [\x09\x0A\x0D\x20-\x7E]           # ASCII
                       | [\xC2-\xDF][\x80-\xBF]            # non-overlong 2-byte
                       |  \xE0[\xA0-\xBF][\x80-\xBF]       # excluding overlongs
                       | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
                       |  \xED[\x80-\x9F][\x80-\xBF]       # excluding surrogates
                       |  \xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
                       | [\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
                       |  \xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
                     )
                    /sx', $s, $m);
    #d($m);
    if (count($m[0]) <= $maxlength) return $s;
    $is_cutted = true;
    $left = implode('', array_slice($m[0], 0, $maxlength));
    #из диапазона ASCII исключаем буквы, цифры, закрывающие парные символы [a-zA-Z\d)}\];]
    #нельзя вырезать в конце строки символ ";", т.к. он используются в сущностях &xxx;
    $left2 = rtrim($left, "\x00..\x28\x2A..\x2F\x3A\x3C\x40\x5C\x5E..\x60\x7C\x7E\x7F");
    if (mb_strlen($left) !== mb_strlen($left2)) return $left2 . $continue;

    #добавляем остаток к обрезанному слову
    $right = implode('', array_slice($m[0], $maxlength));
    preg_match('/^(?: [a-zA-Z\d\)\]\}\-\.]  #английские буквы или цифры, закрывающие парные символы, дефис для составных слов, дата, IP-адреса, URL типа www.ya.ru!
                    | \xe2\x80[\x9d\x99]|\xc2\xbb|\xe2\x80\x9c  #закрывающие кавычки
                    | \xc3[\xa4\xa7\xb1\xb6\xbc\x84\x87\x91\x96\x9c]|\xc4[\x9f\xb1\x9e\xb0]|\xc5[\x9f\x9e]  #турецкие
                    | \xd0[\x90-\xbf\x81]|\xd1[\x80-\x8f\x91]   #русские буквы
                    | \xd2[\x96\x97\xa2\xa3\xae\xaf\xba\xbb]|\xd3[\x98\x99\xa8\xa9]  #татарские
                  )+
                /sx', $right, $m);
    #d($m);
    $right = isset($m[0]) ? rtrim($m[0], '.-') : '';
    $s2 = $left . $right;
    if (mb_strlen($s2) !== mb_strlen($s)) $s2 .= $continue;
    return $s2;
}
?>
