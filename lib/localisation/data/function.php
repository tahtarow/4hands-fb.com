<?php

if (!function_exists('var_export_to_file')){
    function var_export_to_file($var,$patch)
    {
        file::create_dir_for_file_if_not_exist($patch);
        $output = json_decode(str_replace(array('(', ')'), array('&#40', '&#41'), json_encode($var)), true);
        $output = var_export($output, true);
        $output = str_replace(array('array (', ')', '&#40', '&#41'), array('[', ']', '(', ')'), $output);
        $output = '<? return ' . $output . ';';
        return file_put_contents($patch, $output);
    }
}

if (!class_exists('cookies')){
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

}

