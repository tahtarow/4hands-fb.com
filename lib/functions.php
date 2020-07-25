<?php



function dump_to_file($file_name,$vars,$refresh_file=true){

    if ($refresh_file and !isset($GLOBALS['occurred_dump_to_file'])){
        unlink($file_name);
    }
    $GLOBALS['occurred_dump_to_file'] = true;
    file_put_contents($file_name, print_r($vars, 1), FILE_APPEND);
}

function dump($var, $color = '', $i = -1, $key = '')
{
    $i++;
    if ($key == '0_') $key = '&#48;';
    if (is_object($var)) {
        $var = (array)$var;
    }
    if (!is_array($var)) {
        if ($var === false) $var = '<red style="color:red">false</red>';
        if ($var === true) $var = '<red style="color:green">true</red>';
        if (empty($var)) $var = '""';
        if (empty($key) and !$key === 0 and !$key === false) $key = '(var)';

        echo str_repeat('▌', $i) . $key . '=>' . $var . '<br>' . "\n\r";
    } else {
        if (empty($key)) $key = '(arr)';
        if ($key == '0_') $key = '&#48;';
        if (!empty($key)) echo str_repeat('▌', $i) . $key . ':<br>' . "\n\r";
        foreach ($var as $key => $item) {
            if (empty($key)) $key = '0_';
            dump($item, $color, $i, $key);
        }
    }
    if ($i == 0) {
        echo '<br>' . "\n";
        echo '<br>' . "\n";
    }

//    varName($var);
//    if (!is_array($var) and !is_object($var)) {
//        echo '<br><p  style="background-color: '.$color.';">' . $var . '</p><br>';
//    } else {
//        echo '<pre  style="background-color: '.$color.';">';
//        var_dump($var);
//        echo '</pre>';
//    }
}

function str_replace_once($search, $replace, $text)
{
    $pos = strpos($text, $search);
    return $pos !== false ? substr_replace($text, $replace, $pos, strlen($search)) : $text;
}

function dumpex($var = '')
{
    dump($var);
    exit();
}

function dumpt($var)
{
    if (!is_array($var)) {
        dump($var);
    } else {
        echo "<table>";

        echo "<tr>";
        foreach ($var[0] as $key => $value) {
            echo "<td>";
            echo "<b>" . $key . "</b>";
            echo "</td>";

        }
        echo "</tr>";

        foreach ($var as $row) {
            echo "<tr>";

            foreach ($row as $cel) {
                echo "<td>";
                if (!is_array($cel)) {
                    echo $cel;
                } else {
                    dump($cel);
                }
                echo "</td>";
            }
            echo "</tr>";

        }

        echo "</table>";
    }
}

function dumptx($var)
{
    if (!is_array($var)) {
        dump($var);
    } else {
        echo "<table>";

        echo "<tr>";
        foreach ($var[0] as $key => $value) {
            echo "<td>";
            echo "<b>" . $key . "</b>";
            echo "</td>";

        }
        echo "</tr>";

        foreach ($var as $row) {
            echo "<tr>";

            foreach ($row as $cel) {
                echo "<td>";
                if (!is_array($cel)) {
                    echo $cel;
                } else {
                    dump($cel);
                }
                echo "</td>";
            }
            echo "</tr>";

        }

        echo "</table>";
    }
}

function dumpg()
{
    echobr('<dump style="color:green;font-weight:bold;padding-top:10px ">POST</dump>');
    dump($_POST);
    echobr('<dump style="color:green;font-weight:bold;padding-top:10px ">GET</dump>');
    dump($_GET);
    echobr('<dump style="color:green;font-weight:bold;padding-top:10px ">SESSION</dump>');
    dump($_SESSION);
    echobr('<dump style="color:green;font-weight:bold;padding-top:10px ">COOKIE</dump>');
    dump($_COOKIE);
}

function echobr($var = '')
{
    if (!empty($var)) {
        if (is_array($var)) {
            foreach ($var as $key => $val) {

                echo $key . " " . $val;
                echo '<br>';
            }
        } else {
            echo $var;
            echo '<br>';
        }
    }
}

function echobr_($var = "", $color = "")
{

    if (is_array($var)) {
        foreach ($var as $key => $val) {
            echo '-----><span style="color:' . $color . '">' . $key . ' ' . $val . '</span><-----<br>';
        }
    } else {
        echo '-----><span style="color:' . $color . '">' . $var . '</span><-----<br>';
    }

}

function echo_if_exist($var = '')
{
    if (isset($var)) {
        echo $var;
    }
}

function echo_if_exist_br($var, $color = "")
{
    if (!empty($var)) {
        echo '<span style="color:' . $color . '">' . $var . '<br>';
    }
}

function varName($v)
{
    $trace = debug_backtrace();
    $vLine = file(__FILE__);
    $fLine = $vLine[$trace[0]['line'] - 1];
    preg_match("#\\$(\w+)#", $fLine, $match);
    print_r($match);
}

function redirect($path, $method = 0)
{
    if ($method === 0)
        header("Location: " . $path);
    if ($method == "js")
        echo "<script>
      window.top.location.href= '" . $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . $path . "';
  </script>";

}

function refresh($method = 'php')
{
    if ($method == "php") {
        header("Refresh:0");
    } else {
        echo '<script>document.location.reload(true);</script>';
    }
}

function t($var = '')
{
    if (!empty($var)) {
        echo '==============<BR>';
        echo '=>>>' . $var . ' <<<=<BR>';
        echo '==============<BR>';
    } else {
        echo '==============<BR>';
        echo '=>>> TEST <<<=<BR>';
        echo '==============<BR>';
    }

}

function tx($var = '')
{
    if (!empty($var)) {
        echo '==============<BR>';
        echo '=>>>' . $var . ' <<<=<BR>';
        echo '==============<BR>';
    } else {
        echo '==============<BR>';
        echo '=>>> TEST <<<=<BR>';
        echo '==============<BR>';
    }
    exit();
}

function getRandomnString($length = 8)
{
    $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
    $numChars = strlen($chars);
    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $string .= substr($chars, rand(1, $numChars) - 1, 1);
    }
    return $string;
}

function translit($string)
{
    $converter = array(
        'а' => 'a', 'б' => 'b', 'в' => 'v',
        'г' => 'g', 'д' => 'd', 'е' => 'e',
        'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
        'и' => 'i', 'й' => 'y', 'к' => 'k',
        'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r',
        'с' => 's', 'т' => 't', 'у' => 'u',
        'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
        'ь' => '\'', 'ы' => 'y', 'ъ' => '\'',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',

        'А' => 'A', 'Б' => 'B', 'В' => 'V',
        'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
        'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
        'И' => 'I', 'Й' => 'Y', 'К' => 'K',
        'Л' => 'L', 'М' => 'M', 'Н' => 'N',
        'О' => 'O', 'П' => 'P', 'Р' => 'R',
        'С' => 'S', 'Т' => 'T', 'У' => 'U',
        'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
        'Ь' => '\'', 'Ы' => 'Y', 'Ъ' => '\'',
        'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        '0' => '0',
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
        '7' => '7',
        '8' => '8',
        '9' => '9',
    );
    return strtr($string, $converter);
}

function create_empty_object()
{
    return $object = new stdClass();
}

function x_isset($var)
{
    if (isset($var) and !empty($var)) {
        return true;
    } else {
        return false;
    }
}

function errors_on()
{
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}

function alert($message = '')
{
    echo "<script>alert('" . $message . "');</script>";
}

function console($message)
{

    if (is_object($message)) {
        $message = (array)$message;
    }
    if (is_array($message)) {
        foreach ($message as $key => $value) {
            if (is_object($value)) {
                $value = (array)$value;
            }
            if (is_array($value)) {
                console($value);
            } else {
                echo "<script>console.log('" . $key . "_" . $value . "');</script>";
            }
        }
    } else {
        echo "<script>console.log('" . $message . "');</script>";
    }
}


function ajax_answer($array)
{
    echo json_encode($array);
}

function array_reset_key(&$array)
{
    return array_values($array);
}

function in_text($needle, $text)
{
    if (is_array($needle)) {
        foreach ($needle as $needl) {
            if (mb_stripos(mb_strtolower($text, 'UTF-8'), mb_strtolower($needl, 'UTF-8'), 0, 'UTF-8') !== FALSE) {
                $match = true;
                break;
            } else {
                $match = false;
            }
        }
        return $match;

    } elseif (mb_stripos(mb_strtolower($text, 'UTF-8'), mb_strtolower($needle, 'UTF-8'), 0, 'UTF-8') !== FALSE) {
        return true;
    } else {
        return false;
    }

}

function all_in_text($needle, $text)
{
    if (is_array($needle)) {
        foreach ($needle as $needl) {
            if (stripos(mb_strtolower($text), mb_strtolower($needl)) !== FALSE) {
                $match = true;
            } else {
                $match = false;
                break;
            }
        }
        return $match;

    } elseif (stripos(mb_strtolower($text), mb_strtolower($needle)) !== FALSE) {
        return true;
    } else {
        return false;
    }

}

function in_text_register($needle, $text)
{
    if (is_array($needle)) {

        foreach ($needle as $needl) {
            if (strpos($text, $needl) !== FALSE) {
                $match = true;
                break;
            } else {
                $match = false;
            }
        }
        return $match;

    } elseif (strpos($text, $needle) !== FALSE) {
        return true;
    } else {
        return false;
    }

}

function sortKeysDescBAD(&$arrNew)
{
    $arrKeysLength = array_map('strlen', array_keys($arrNew));
    array_multisort($arrKeysLength, SORT_DESC, $arrNew);
    //return max($arrKeysLength);
}

function sortKeysDescGOOD(&$arrNew)
{
    uksort($arrNew, function ($a, $b) {
        $lenA = strlen($a);
        $lenB = strlen($b);
        if ($lenA == $lenB) {
            // If equal length, sort again by descending
            $arrOrig = array($a, $b);
            $arrSort = $arrOrig;
            rsort($arrSort);
            if ($arrOrig[0] !== $arrSort[0]) return 1;
        } else {
            // If not equal length, simple
            return $lenB - $lenA;
        }
    });
}

function var_export_to_file($var,$patch)
{
    file::create_dir_for_file_if_not_exist($patch);
    $output = json_decode(str_replace(array('(', ')'), array('&#40', '&#41'), json_encode($var)), true);
    $output = var_export($output, true);
    $output = str_replace(array('array (', ')', '&#40', '&#41'), array('[', ']', '(', ')'), $output);
    $output = '<? return ' . $output . ';';
    return file_put_contents($patch, $output);
}

function rec_var_to_file($data, $filename)
{
    $data = arrayz::convert_to_str($data);
    file_put_contents($filename, $data);
}

function rec_var_to_file2($data, $filename)
{
    $data = arrayz::convert_to_str2($data);
    file_put_contents($filename, $data);
}

function load_var_from_file($filename)
{
    $data = file_get_contents($filename);
    return arrayz::convert_from_str($data);
}

function load_var_from_file2($filename)
{
    $data = file_get_contents($filename);
    return arrayz::convert_from_str2($data);
}

//перебор строки UTF-8 по символу
function nextchar($string, &$pointer)
{
    if (!isset($string[$pointer])) return false;
    return mb_substr($string, $pointer++, 1, 'UTF-8');
    //                $pointer = 0;
//                while(($chr = nextchar($query, $pointer)) !== false){
//                    echobr($chr) ;
//                }
}

class file
{
    static function from_post_save($files_name, $patch, $rewrite = 0)
    {
        $names = '';
        if (!is_array($_FILES["$files_name"]["error"])) {
            $temp1 = $_FILES["$files_name"]["error"];
            $temp2 = $_FILES["$files_name"]["tmp_name"];
            $temp3 = $_FILES["$files_name"]["name"];

            $_FILES["$files_name"]["error"] = array();
            $_FILES["$files_name"]["tmp_name"] = array();
            $_FILES["$files_name"]["name"] = array();

            $_FILES["$files_name"]["error"][] = $temp1;
            $_FILES["$files_name"]["tmp_name"][] = $temp2;
            $_FILES["$files_name"]["name"][] = $temp3;
        }


        foreach ($_FILES[$files_name]["error"] as $key => $error) {
            $names = [];
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["$files_name"]["tmp_name"][$key];
                // basename() может спасти от атак на файловую систему;
                // может понадобиться дополнительная проверка/очистка имени файла
                $name = basename($_FILES["$files_name"]["name"][$key]);
                $name_temp = explode('.', $name);
                $name_temp[count($name_temp) - 1] = mb_strtolower($name_temp[count($name_temp) - 1]);

                $name = '';
                $d = '';
                foreach ($name_temp as $n) {
                    $name .= $d . $n;
                    $d = '.';
                }
                if ($rewrite === 0) {
                    $new_name = $name;
                    while (file_exists($patch . '/' . $new_name)) {
                        $name1 = explode('.', $name);
                        $new_name = $name1[0] . '_' . getRandomnString() . '.' . $name1[1];
                    }
                    $name = $new_name;
                }
                $names[] = $name;
                $patch .='/'.$name;
                self::create_dir_for_file_if_not_exist($patch);
                move_uploaded_file($tmp_name, $patch);
            }
        }
        if (empty($names)) {
            return false;
        } else {
            return $names;
        }
    }

    static function create_dir_for_file_if_not_exist($file){
        $dir = pathinfo($file)['dirname'];
        if (!file_exists($dir)){
            mkdir($dir, 0777, true);
        }
    }
}



class errors
{
    public static $list = [];

    static function on()
    {
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
    }

    static function off()
    {
        error_reporting(0);
    }
}

class timer
{
    static $start_time;

    static function start()
    {
        self::$start_time = microtime(true);
    }

    static function result()
    {
        if (isset(self::$start_time)) echo 'time: ' . round(microtime(true) - self::$start_time, 4) . ' сек.<br>' . "\n";

    }

}

function get_de_month($month_no)
{
    switch ($month_no) {
        case 1:
            return "Jan";
            break;
        case 2:
            echo "Feb";
            break;
        case 3:
            echo "Mär";
            break;
        case 4:
            echo "Apr";
            break;
        case 5:
            echo "Mai";
            break;
        case 6:
            echo "Jun";
            break;
        case 7:
            echo "Jul";
            break;
        case 8:
            echo "Aug";
            break;
        case 9:
            echo "Sep";
            break;
        case 10:
            echo "Okt";
            break;
        case 11:
            echo "Nov";
            break;
        case 12:
            echo "Dez";
            break;
    }
}

class  arrayz
{

    static function convert_to_str($array)
    {
        return (serialize($array));
    }

    static function convert_from_str($array)
    {
        return unserialize(($array));

    }

    static function convert_to_str2($array)
    {
        return base64_encode(serialize($array));
    }

    static function convert_from_str2($array)
    {
        return unserialize(base64_decode($array));

    }


    static function insert_in_array($array, $pos, $ins)
    {
        return $res = array_merge(array_slice($array, 0, $pos), $ins, array_slice($array, $pos));
    }

    private static function sort_func_asc($a, $b) //объявляем функцию
    {

        if (strlen($a) == strlen($b)) //если длины значений в переменных $a и $b равны возвращаем 0 (закомментировано)
        {
            //  return 0;
        }
        //если длина значения в переменной $a меньше длины значения в переменной $b, то возвращаем -1, иначе возвращаем 1
        return (strlen($a) < strlen($b)) ? -1 : 1;
    }

    private static function sort_func_desc($a, $b) //объявляем функцию
    {

        if (strlen($a) == strlen($b)) //если длины значений в переменных $a и $b равны возвращаем 0 (закомментировано)
        {
            //  return 0;
        }
        //если длина значения в переменной $a меньше длины значения в переменной $b, то возвращаем -1, иначе возвращаем 1
        return (strlen($a) > strlen($b)) ? -1 : 1;
    }

    static function sort_array_on_lenght_asc($arr)
    {
        usort($arr, "self::sort_func_asc");
        return $arr;
    }

    static function sort_array_on_lenght_desc($arr)
    {
        usort($arr, "self::sort_func_desc");
        return $arr;
    }

}

if (!function_exists("mb_str_replace")) {
    function mb_str_replace($needle, $replace_text, $haystack)
    {
        return implode($replace_text, mb_split($needle, $haystack));
    }
}

class cookies
{

    static function set_use_js($name, $value, $time = null)
    {
        echo "<script>
    if(condition){
      document.cookie = \"" . $name . "=" . $value . "; path=/; \" ;
    }
  </script>";
    }

    static function del_use_js($name)
    {
        echo "<script>
    if(condition){
        var date = new Date;
date.setDate(date.getDate() + 1);
alert( date.toUTCString() );

      var date = new Date(0);
document.cookie = \"" . $name . "=; path=/; expires=\" + date.toUTCString();
    }
  </script>";
    }

    static function set($name, $value, $time = null)
    {
        if ($time == null) $time = time() + (365 * 24 * 60);
        setcookie("$name", $value, time() + (365 * 24 * 60), '/');
    }

    static function del($name)
    {
        if (is_array($name)) {
            foreach ($name as $name1) {
                if (isset($_COOKIE[$name1]))
                    setcookie("$name1", '', time() - 1, '/');
            }
        } else {
            if (isset($_COOKIE[$name]))
                setcookie("$name", '', time() - 1, '/');
        }
    }
}


function get_date_time_for_bd()
{
    return date('Y-m-d H:i:s');
}


function autoload_class_from($dir)
{
    define('start_dir_for_autoload_class', $dir);

}




spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    include start_dir_for_autoload_class . '/' . $class . '.php';
});

