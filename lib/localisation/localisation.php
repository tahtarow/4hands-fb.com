<?php
include_once __DIR__ . '/data/function.php';

class localisation
{
    public static
        $lang = 'ru',
        $main_lang = 'ru',
        $dir_for_translate = __DIR__ . '/data/langs',
        $auto_translate_to_created_lang_package = false,
        $url_for_activation_translate_process;

    private static
        $data;

    public static function set_locale($lang)
    {
        self::detect_locale($lang);
    }

    public static function detect_locale($lang = '')
    {
        if (!empty($lang)) {
            self::$lang = $lang;
        } elseif (isset($_COOKIE['lang'])) {
            self::$lang = $_COOKIE['lang'];
        } else {
            preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)(?:;q=([0-9.]+))?/', strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"]), $matches); // Получаем массив $matches с соответствиями
            $t_langs = array_combine($matches[1], $matches[2]); // Создаём массив с ключами $matches[1] и значениями $matches[2]
            $langs = [];
            foreach ($t_langs as $n => $v) {
                $v = $v ? $v : 1; // Если нет q, то ставим значение 1
                $n = explode('-', $n)[0];
                if (isset($langs[$n])) {
                    $langs[$n] < $v ? $v : $langs[$n];
                } else {
                    $langs[$n] = $v;
                }
            }
            arsort($langs);
            if (key($langs) == self::$main_lang) {
                self::$lang = self::$main_lang;
            } else {
                self::$lang = 'en';
//                foreach ($langs as $lang => $val) {
//                    if (file_exists(self::$dir_for_translate . '/' . $lang . '.php')) {
//                        self::$lang = $lang;
//                        cookies::set('lang', $lang);
//                        $detect = 1;
//                        break;
//                    }
//                }
//                if ($detect) {
//                    self::$lang = 'en';
//                }
            }
        }
        cookies::set('lang', self::$lang);
        self::$data = self::load_translate(self::$lang);

    }

    static function txt($txt)
    {
        if (isset(self::$data[$txt])) {
            return self::$data[$txt];
        } else {
            if (self::$lang == self::$main_lang) {
                self::$data[$txt] = $txt;
                var_export_to_file(self::$data, __DIR__ . '/data/' . self::$main_lang . '.php');
                if (self::$auto_translate_to_created_lang_package) {
                    $langs = self::get_all_langs();
                    $tasks = self::get_tasks();
                    foreach ($langs as $lang) {
                        $lang = explode('.', $lang)[0];
                        if (!isset($tasks[$txt][$lang])) {
                            $tasks[$txt][$lang] = 1;
                        }
                    }
                    var_export_to_file($tasks, __DIR__ . '/data/task.php');

                }
                return $txt;
            } elseif (!empty(self::$lang)) {
                if (!file_exists(__DIR__ . '/data/task.php')) {
                    file_put_contents(__DIR__ . '/data/task.php', '<? return null;');
                }

                $main_lang_package = self::load_translate(self::$main_lang);
                if (!empty($txt) and !isset($main_lang_package[$txt])) {
                    $main_lang_package[$txt] = $txt;
                    var_export_to_file($main_lang_package, __DIR__ . '/data/' . self::$main_lang . '.php');
                }


                $tasks = self::get_tasks();
                if (!isset($tasks[$txt][self::$lang])) {
                    $tasks[$txt] = [self::$lang => ''];
                }
                var_export_to_file($tasks, __DIR__ . '/data/task.php');
                return '|...translate...|';
            }
        }
    }

    public static function load_translate($lang)
    {
        if ($lang == self::$main_lang) {
            if (file_exists(__DIR__ . '/data/' . $lang . '.php')) {
                return include __DIR__ . '/data/' . $lang . '.php';
            } else {
                file_put_contents(__DIR__ . '/data/' . $lang . '.php', '<? return null;');
                return '';
            }
        } elseif (file_exists(self::$dir_for_translate . '/' . $lang . '.php')) {
            return include self::$dir_for_translate . '/' . $lang . '.php';
        } else {
            file_put_contents(self::$dir_for_translate . '/' . $lang . '.php', '<? return null;');
            return '';
        }

    }

    public static function get_tasks()
    {
        if (file_exists(__DIR__ . '/data/task.php')) {
            return include __DIR__ . '/data/task.php';
        } else {
            file_put_contents(__DIR__ . '/data/task.php', '<? return null;');
            return '';
        }
    }

    public static function set_translated_txt($txt_key, $lang, $translation)
    {
        if (!file_exists(self::$dir_for_translate . '/' . $lang . '.php')) {
            file_put_contents(self::$dir_for_translate . '/' . $lang . '.php', '<? return null;');
        }

        $translate = self::load_translate($lang);
        if (!isset($translate[$txt_key])) {
            $translate[$txt_key] = $translation;
            var_export_to_file($translate, self::$dir_for_translate . '/' . $lang . '.php');
        }

        $tasks = self::get_tasks();
        if (isset($tasks[$txt_key][$lang])) {
            unset($tasks[$txt_key][$lang]);
            if (empty($tasks[$txt_key])) {
                unset($tasks[$txt_key]);
            }
            var_export_to_file($tasks, __DIR__ . '/data/task.php');
        }


    }

    public static function create_localisation_for($translate_to)
    {
        if ($translate_to <> 'all') {
            $main_txt = self::load_translate(self::$main_lang);
            $translation = self::load_translate($translate_to);
            $tasks = self::get_tasks();

            if (!empty($main_txt)) {
                foreach ($main_txt as $txt) {
                    if (!isset($translation[$txt]) and !isset($tasks[$txt][$translate_to])) {
                        if (!empty($txt) and !empty($translate_to)) {
                            $tasks[$txt][$translate_to] = 1;
                        }
                    }
                }
                if (!empty($tasks)) {
                    var_export_to_file($tasks, __DIR__ . '/data/task.php');
                }
            }
        }
    }

    public static function get_script_for_google_translit()
    {
        include __DIR__ . '/data/scpipt.js';
    }

    public static function handler()
    {

        header('Access-Control-Allow-Origin: *');

        if (isset($_POST['check_task'])) {
            $tasks = localisation::get_tasks();

            if (!empty($tasks)) {
                foreach ($tasks as $key => $t) {
                    $txt = $key;
                    foreach ($t as $tkey => $tt) {
                        $lang = $tkey;
                        break;
                    }
                    $task = [
                        'txt' => $txt,
                        'lang' => $lang
                    ];
                    break;
                }
            } else {
                $task = ['no_task' => 1];
            }
            echo json_encode($task);
        } elseif (isset($_POST['result'])) {

            $txt = $_POST['result']['txt'];
            $translation = $_POST['result']['translated_txt'];
            $lang = $_POST['result']['lang'];
            localisation::set_translated_txt($txt, $lang, $translation);

            echo json_encode(['no_task' => 1]);


        } else {
            localisation::get_script_for_google_translit();
        }
    }

    public static function get_all_langs()
    {
        $langs = [];
        if ($handle = opendir(self::$dir_for_translate)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    $langs[] = $file;
                }
            }
            closedir($handle);
        }
        return $langs;
    }
}