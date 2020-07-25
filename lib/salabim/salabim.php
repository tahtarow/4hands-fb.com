<?php

class salabim
{
    public
        $cache = true,
        $colore_blocks = false,
        $cach_differenc_time = 0,
        $cache_dir = './templates_cache/';
    private
        $patch_to_template = '',
        $patch_to_templates_dir = false,
        $patch_to_cache_file,
        $cache_file_name,
        $tamplate,
        $test_i=0,
        $template_file_name,
        $php_parts;


    public function get($file)
    {
        $this->tamplate = $file;
        $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        if (!file_exists($file)) {
            echo 'error file ' . $file . ' not exist';
            return false;
        } else {
            $f = new SplFileInfo($file);
            $this->patch_to_template = $f->getPath();
            $this->template_file_name = $f->getFilename();
            $this->cache_file_name = md5($url . $file);
            if (stripos(':', $this->patch_to_template)) {
                $this->patch_to_template = explode(':', $this->patch_to_template)[1];
            }
            $this->patch_to_cache_file = $this->cache_dir . '/' . $this->cache_file_name . '.php';
            if (!file_exists($this->patch_to_cache_file)) {
                return $this->process_template();
            } else {
                if ($this->cache) {
                    $cash = $this->check_cach();
                    if ($cash === false) {
                        return $this->process_template();
                    } else {
                        return $cash;
                    }
                } else {
                    return $this->process_template();
                }

            }
        }
    }

    private function proces_psevdo_php_code_1($templ)
    {



        $php_code_with_braces = [
            ' if(' => '<? ',
            '}else{' => '<? ',
            '}elseif(' => '<? ',
            ' for(' => '<? ',
            ' foreach(' => '<? ',
        ];
        $operator_pos = false;
        $operator = false;
        $open_tag = '';
        foreach ($php_code_with_braces as $op => $tag) {
            $p = mb_stripos($templ, $op);
            if ($operator_pos === false and (!$p === false or $p === 0)) {
                $operator_pos = $p;
                $operator = $op;
                $open_tag =$tag;
            } else {
                if (!$p===false) {
                    if ($p < $operator_pos) {
                        $operator_pos = $p;
                        $operator = $op;
                        $open_tag =$tag;
                    }
                }
            }
        }


        if (!$operator === false) {
            $start = mb_stripos($templ, '{', $operator_pos);
            $end = $this->find_php_block_end($templ, $start+1);

            $close_tag = ' ?>';
            $open_tag2 = '<? ';
            $else_fix = 0;
            if($operator==' if('){
                if(in_text('else',mb_substr($templ, $end, 6))){
                    $close_tag = '';
                    $open_tag2= '';
                }
            }
            if($operator=='}else{'or $operator=='}elseif('){
                $else_fix = 1;
            }
                $templ =
                mb_substr($templ, 0, $operator_pos-$else_fix).
                $open_tag.mb_substr($templ, $operator_pos, $start-$operator_pos+1).' ?>'.

                mb_substr($templ, $start+1, $end-$start-1).
                $open_tag2.mb_substr($templ, $end, 1).$close_tag.mb_substr($templ, $end+1);



            $templ = $this->cut_php_code_in_php_tags($templ);

            $templ = $this->proces_psevdo_php_code_1($templ);
            if ($templ===true){
                return $templ = $this->proces_psevdo_php_code_1($templ);
            }else{
                return $templ;
            }




//            $php_tags_open = false;
//            $pseudo_php_block_start = false;
//            $detect_block_start = false;
//            $opened_breckets = 0;
//            $code_in_current_block = '';
//            $result = '';
//
//
//
//
//            $templ_lenght = strlen($templ);
//            for ($i = $operator_pos; $i < $templ_lenght; $i++) {
//                $result .= $templ[$i];
//
//                //detect block in php tags
//                if (!$pseudo_php_block_start) {
//                    if ($templ[$i] . @$templ[$i + 1] == '<?') {
//                        $php_tags_open = true;
//                    }
/*                    if (isset($templ[$i - 2]) and $templ[$i - 2] . @$templ[$i - 1] == '?>') {*/
//                        $php_tags_open = false;
//                    }
//                }
//
//
//                //process pseudo code
//                if (!$php_tags_open) {
//
//                    //если блок еще не найден пытаемся найти
//                    if (!$pseudo_php_block_start) {
//                        foreach ($php_code_with_braces as $needle => $tag) {
//                            if ($i == 0) {
//                                str_replace(' ', '', $needle);
//                            }
//                            $search_fragment = '';
//                            $needle_lenght = strlen($needle);
//                            for ($ii = 0; isset($templ[$i + $ii]); $ii++) {
//                                $search_fragment .= $templ[$i + $ii];
//                                if ($search_fragment == $needle) {
//                                    $pseudo_php_block_start = true;
//                                    $result = substr($result, 0, -1);
//                                    $result .= $tag . $templ[$i];
//                                }
//                            }
//                        }
//                    }
//                    //END если блок еще не найден пытаемся найти
//
//                    //если блок нашло то считаем скобки и вытягиваем его содержание
//                    if ($pseudo_php_block_start) {
//                        $change_detected = true;
//
//                        if ($templ[$i] == '{') {
//                            if (!$detect_block_start) {
/*                                $result .= ' ?>';*/
//                            }
//                            $detect_block_start = true;
//                            $opened_breckets++;
//                        }
//                        if ($templ[$i] == '}') {
//                            $opened_breckets--;
//                        }
//
//                        if ($opened_breckets > 0) {
//                            $code_in_current_block .= $templ[$i];
//                        }
//
//                        //если найден конец блока
//                        if ($detect_block_start and $opened_breckets == 0) {
//                            $detect_block_start = false;
//                            $pseudo_php_block_start = false;
//
//                            $result = substr($result, 0, -1);
//                            $result .= '<? ' . $templ[$i];
//
//                            $code_in_current_block = substr($code_in_current_block, 0, -1);
//                            $code_in_current_block = substr($code_in_current_block, 0, -1);
//                            $code_in_current_block = substr($code_in_current_block, 0, -1);
//                            $code_in_current_block = substr($code_in_current_block, 2);
//
//                        }
//                        //END если найден конец блока
//                    }
//                    //END если блок нашло то считаем скобки и вытягиваем его содержание
//                }
//
//
//            }
//
/*            $result = str_replace('<? }', '<? }?>', $result);*/
/*            $result = str_replace('<? }?>else{ ?>', '<? }else{ ?>', $result);*/
/*            $result = str_replace('?>?>', '?>', $result);*/
//            $result = $this->prepare_php($result);
//
//            return ($result);

            return true;
        }
        return $templ;
    }

    private function prepare_templ($templ)
    {
        $templ = str_replace('}', '} ', $templ);
        $templ = preg_replace('/ {2,}/', ' ', $templ);
        $templ = str_replace(' {', '{', $templ);
        $templ = str_replace(' }', '}', $templ);
        $templ = str_replace('} ', '}', $templ);
        $templ = str_replace('}if(', '} if(', $templ);
        $templ = str_replace('{if(', '{if(', $templ);
        $templ = str_replace('?>', '?>', $templ);
        return $templ;
    }

    private function proces_html($templ)
    {

        $templ = str_replace(array("\r\n", "\r", "\n", "\t"), '', $templ);
        $blocks = [];
        $block_number = 0;
        $detected_block_start = 0;
        $opened_breckeds = 0;

        for ($i = 0; $i < strlen($templ); $i++) {
            if ($detected_block_start == 0) {
                if ($templ[$i] <> ' ' or isset($blocks[$block_number]['prefix'])) {
                    if (!isset($blocks[$block_number]['prefix'])) $blocks[$block_number]['prefix'] = '';
                    $blocks[$block_number]['prefix'] .= $templ[$i];
                }
            } else {
                if (!isset($blocks[$block_number]['block'])) $blocks[$block_number]['block'] = '';
                $blocks[$block_number]['block'] .= $templ[$i];
            }

            if ($templ[$i] == '{' and $opened_breckeds == 0) {
                $blocks[$block_number]['prefix'] = substr($blocks[$block_number]['prefix'], 0, -1);
                $detected_block_start = 1;
                $opened_breckeds++;
            } elseif ($templ[$i] == '{') {
                $opened_breckeds++;
            }

            if ($templ[$i] == '}') {
                $opened_breckeds--;
                if ($opened_breckeds == 0) {
                    $blocks[$block_number]['block'] = substr($blocks[$block_number]['block'], 0, -1);
                    $block_number++;
                }
            }
            if (!isset($blocks[$block_number]['prefix'])) $blocks[$block_number]['prefix'] = '';
            $blocks[$block_number]['prefix'] = preg_replace('/ {2,}/', ' ', $blocks[$block_number]['prefix']);


            if ($opened_breckeds == 0) {
                $detected_block_start = 0;
            }
        }

        foreach ($blocks as &$block) {
            if (isset($block['prefix'])) {

                $temp = $this->process_prefix($block['prefix']);


                if (in_text('<css', $temp['prefix'])) {
                    if (in_text(',', $block['block'])) {
                        $links = explode(',', $block['block']);
                        $temp['prefix'] = '';
                        foreach ($links as $link) {
                            $link = str_replace("\n", "", $link);
                            $link = str_replace("\r", "", $link);
                            if (!empty($link)) {
                                $temp['prefix'] .= "\n" . '<link href="' . $link . '" rel="stylesheet" type="text/css">';
                            }
                        }
                        $temp['postfix'] = '';
                    } else {
                        $temp['prefix'] = str_replace('<css', '<link', $temp['prefix']);
                        $temp['prefix'] .= ' href="' . $block['block'] . '" rel="stylesheet" type="text/css"';
                    }
                    $block['block'] = '';
                }

                if (in_text('<js', $temp['prefix'])) {

                    if (in_text(',', $block['block'])) {
                        $links = explode(',', $block['block']);
                        $temp['prefix'] = '';
                        foreach ($links as $link) {
                            $link = str_replace("\n", "", $link);
                            $link = str_replace("\r", "", $link);
                            if (!empty($link)) {
                                $temp['prefix'] .= "\n" . '<script src="' . $link . '"></script>';
                            }
                        }
                        $temp['postfix'] = '';
                    } else {
                        $temp['prefix'] = str_replace('<js', '<script ', $temp['prefix']);
                        $temp['prefix'] .= ' src="' . $block['block'] . '"></script';
                    }
                    $block['block'] = '';
                }

                if (in_text('<meta', $temp['prefix'])) {
                    $temp['prefix'] .= ' ' . $block['block'];
                    $block['block'] = '';
                }

                if (in_text('<html', $temp['prefix'])) {
                    $temp['prefix'] = str_replace('<html', '<!DOCTYPE html><html', $temp['prefix'] . "\n");
                }

                $block['prefix'] = $temp['prefix'];
                if (isset($temp['postfix'])) {
                    $block['postfix'] = $temp['postfix'];
                }
            }

            if (isset($block['block'])) {
                if (in_text('{', $block['block'])) {
                    $block['block'] = $this->proces_html($block['block']);
                } else {
                    $block['block'] = $this->process_php_var_in_html($block['block']);
                }
            }
            $block['postfix'] = $block['postfix'] . "\n";
        }
        $blocks = $this->assembly_html_blocks($blocks);
        return $blocks;

    }

    private function process_prefix($prefix)
    {
        $id = '';
        $class = '';
        $style = '';
        $prefix_0 = '';

        if (in_text('@', $prefix)) {
            preg_match('/@(.*)@/', $prefix, $style);
            $prefix = str_replace($style[0], '', $prefix);
            $style = $style[1];
        }

        $extra = '';
        if (in_text('*', $prefix)) {
            preg_match('/\*(.*)\*/', $prefix, $extra);

            $prefix = str_replace($extra[0], '', $prefix);
            $extra = ' ' . $extra[1] . ' ';
        }

        if (in_text(' ', $prefix)) {
            $temp = explode(' ', $prefix);
            $prefix_0 = str_replace(end($temp), '', $prefix);
            $prefix = end($temp);
        }

        $tag = '';
        $classes = '';
        $ids = '';
        $post_fix = '';
        $close_open_teg = '';

        if (isset($prefix[0])) {
            if ($prefix[0] == '.' or $prefix[0] == '#') {
                $tag = '<div ';
                $post_fix = '</div>';
                $close_open_teg = '>';

            } elseif (!empty($prefix) and $prefix[0] <> '.' and $prefix[0] <> '#') {
                preg_match('/[a-zA-Z]+.|[a-zA-Z]+{/', $prefix, $tag);
                if (strlen($prefix) == 1) {
                    preg_match('/[a-zA-Z]|[a-zA-Z]+{/', $prefix, $tag);
                }

                $dot_test =substr($tag[0], -1);
                if ($dot_test == '.' or $dot_test == '#'){
                    $tag[0] =  substr($tag[0], 0, -1);
                    $prefix = $dot_test.$prefix;
                }

                if (in_text('form', $prefix) and in_text('(', $prefix)) {
                    $method = '';

                    $temp2 = $this->extract_data_in_brackets($prefix);
                    $prefix = $temp2['prefix'];
                    $ctx_in_brackets = $temp2['ctx_in_brackets'];

                    if (in_text('post', $prefix)) {
                        $method = ' method = "post" ';
                    }
                    if (in_text('get', $prefix)) {
                        $method = ' method = "get" ';
                    }

                    $action = str_replace(['"', "'"], '', $ctx_in_brackets);
                    $action = ' action="' . $action . '" enctype="multipart/form-data" ';
                    $extra = $method . $action . $extra;
                    $prefix = 'form';
                    $tag[0] = 'form';
                }

                if (in_text('input', $prefix)) {
                    if (in_text('(', $prefix)) {

                        $temp2 = $this->extract_data_in_brackets($prefix);
                        $prefix = $temp2['prefix'];
                        $ctx_in_brackets = $temp2['ctx_in_brackets'];

                        $temp3 = $this->extract_param_between_slasch($ctx_in_brackets);
                        $name = ' name = "' . $temp3['0'] . '" ';
                        $value = ' value = "' . $temp3['1'] . '" ';
                        $extra = $name . $value . $extra;

                    }

                    $prefix = str_replace('(' . $ctx_in_brackets . ')', '', $prefix);
                    if (in_text(':', $prefix)) {
                        $type = ' type = "' . explode(':', $prefix)[1] . '" ';
                        $prefix = 'input';
                        $tag[0] = 'input';
                    }
                    $extra = $type . $extra;
                }


                if (in_text('a=', $prefix)) {
                    $tag = 'a';
                    $prefix = substr($prefix, 2);
                    $temp1 = '';
                    $count_brakts = 0;
                    for ($i = 0; isset($prefix[$i]); $i++) {
                        if ($prefix[$i] == "'" or $prefix[$i] == '"') $count_brakts++;

                        if ($count_brakts < 4 and $count_brakts > 0) {
                            $temp1 .= $prefix[$i];
                        }

                        if ($prefix[$i] == "'" or $prefix[$i] == '"') $count_brakts++;
                    }
                    $prefix = str_replace($temp1, '', $prefix);
                    $temp1 = substr($temp1, 0, -1);
                    $temp1 = substr($temp1, 1);

                    $extra = 'href="' . $temp1 . '" ' . $extra;

                } else {
                    $tag = $tag[0];
//                    $prefix = str_replace($tag, '', $prefix);
//                    dump(explode($tag,$prefix)[1]);
//                    dump(explode($tag,$prefix)[0]);
                    $prefix = explode($tag, $prefix)[1];
                    $tag = str_replace(['.', '#'], '', $tag);
                }

                if (!empty($extra)) {
                    $tag = $tag . ' ';

                }

                if (in_text(['meta', 'css', 'js', 'input', 'br', 'hr'], $tag)) {
                    $post_fix = '>';
                } else {
                    $close_open_teg = '>';
                    $post_fix = '</' . $tag . '>';
                }
                if ($post_fix == '</>') {
                    $post_fix = '';
                    $close_open_teg = '';
                } else {
                    $tag = '<' . $tag . $extra;
                }


            }


        }


        if (isset($prefix[0])) {
            if ($prefix[0] == '.' or $prefix[0] == '#') {
                if ($prefix == '.') { $prefix = '';}

                preg_match_all("/\.[a-zA-Z0-9-_$]+/", $prefix, $classes);
                preg_match_all('/\#[a-zA-Z0-9-_$]+/', $prefix, $ids);

                if (!empty($ids)) {
                    $id = "";
                    if (count($ids[0]>1))$ids[0] =  arrayz::sort_array_on_lenght_desc($ids[0]);
                    foreach ($ids[0] as $id_) {
                        if (!empty($id_)) {
                            $prefix = str_replace($id_, '', $prefix);
                            $id_ = str_replace('#', ' ', $id_);
                            $id .= $id_;
                        }
                    }
                    if (!empty($id)) {
                        $id = " id='" . $id . "' ".$extra;
                    }
                }

                if (!empty($classes)) {

                    if (count($classes[0]>1))$classes[0] =  arrayz::sort_array_on_lenght_desc($classes[0]);

                    $class = "";
                    foreach ($classes[0] as $classes_) {
                        if (!empty($classes_)) {
                            $prefix = str_replace($classes_, '', $prefix);
                            $classes_ = str_replace('.', ' ', $classes_);
                            $class .= $classes_;
                        }
                    }
                    if (!empty($class)) {
                        $class = " class='" . $class . "' ".$extra;
                    }
                }
            }
        }

        if ($this->colore_blocks and !empty($tag)
            and ($tag <> '<css' and
                $tag <> '<js' and
                $tag <> '<meta'
            )
        ) {
            $style = 'background-color:' . $this->random_html_color() . ';';
        }
        if (!empty($style)) {
            $style = " style='" . $style . "'";
        }
        $prefix = $prefix_0 . $tag . $id . $class . $style . $close_open_teg . $prefix;

        return ['prefix' => $prefix, 'postfix' => $post_fix];
    }

    private function process_php_var_in_html($data)
    {
        $var_detected = 0;
        $result = '';

        for ($i = 0; $i < strlen($data); $i++) {
            if ($data[$i] == '$' and ctype_alpha($data[$i + 1])) {
                $var_detected = 1;
                $result .= '<?=';

            }
            if ($data[$i] <> '}') {
                $result .= $data[$i];
            }

            if ($var_detected == 1) {
                if (
                    (!(ctype_alpha($data[$i]) or
                        is_numeric($data[$i]) or
                        $data[$i] == "$" or
                        $data[$i] == "_" or
                        $data[$i] == "[" or
                        $data[$i] == "]" or
                        $data[$i] == "'" or
                        $data[$i] == '"' or
                        $data[$i + 1] == "[" or
                        $data[$i + 1] == "]"))
                    or
                    !isset($data[$i + 1])
                ) {
                    $var_detected = 0;
                    $result .= '?>';
                }
            }

        }
        if (substr_count($result, '{') < substr_count($result, '}')) {
            $result = substr($result, 0, -1);
        }
        return $result;
    }

    private function assembly_html_blocks($blocks)
    {
        $result = '';
        foreach ($blocks as $block) {
            if (!isset($block["block"])) $block["block"] = '';
            if (is_array($block["block"])) {
                $result .= $this->assembly_html_blocks($block["block"]);
            } else {
                $result .= $block["prefix"] . $block["block"] . $block["postfix"];
            }
        }

        return $result;
    }

    private function cut_php_code_in_php_tags($templ)
    {
        $php_blocs = [];
        if (isset($this->php_parts)) {
            $block_number = count($this->php_parts);
        } else {
            $block_number = 0;
        }

        $detect_block_start = 0;
        for ($i = 0; isset($templ[$i + 1]); $i++) {

            if ($templ[$i] . $templ[$i + 1] == '<?') {
                $detect_block_start = 1;
                $block_number++;
            }
            if (isset($templ[$i - 2]) and $templ[$i - 2] . $templ[$i - 1] == '?>') {
                $detect_block_start = 0;
            }

            if ($detect_block_start == 1) {
                if (!isset($php_blocs['$$$$' . $block_number . '$ '])) $php_blocs['$$$$' . $block_number . '$ '] = '';
                $php_blocs['$$$$' . $block_number . '$ '] .= $templ[$i];
            }


        }

        foreach ($php_blocs as $key => $block) {
            $templ = str_replace($block, $key, $templ);
        }

        if (!empty($this->php_parts)) {
            $this->php_parts = array_merge($this->php_parts, $php_blocs);
        } else {
            $this->php_parts = $php_blocs;
        }
        return $templ;
    }

    private function prepare_php($templ)
    {
        $templ = str_replace("if(", "  if(", $templ);
        $templ = preg_replace('/ {2,}/', ' ', $templ);

        return $templ;

    }

    private function limiter($limit)
    {
        $this->test_i++;
        if ($this->test_i > $limit) {
            tx('LIMIT REACHED');
        } else {
            return $this->test_i;
        }
    }

    private function proces_psevdo_php_code($templ)
    {

        preg_match_all('/\|[^\|]{2,50}\|+/', $templ, $vars);

        if (!empty($vars)) {
            foreach ($vars[0] as $var) {
                $var_ = substr($var, 0, -1);
                $var_ = substr($var_, 1);
                if (!empty($var)) {
                    $templ = str_replace($var, '<?=' . $var_ . '?>', $templ);
                }
            }

        }


        $result = $this->proces_psevdo_php_code_1($templ);
        return $result;
    }

    private function restore_php_code($templ)
    {
        foreach ($this->php_parts as $key => $part) {
            $templ = str_replace($key, $part, $templ);
        }
        return $templ;
    }

    private function random_html_color()
    {
        return sprintf('#%02X%02X%02X', rand(0, 255), rand(0, 255), rand(0, 255));
    }

    public function clear_cache()
    {
        $this->delTree($this->cache_dir);
        if (!file_exists($this->cache_dir)) {
            if (!mkdir($this->cache_dir, 0777, true)) {
                die('Не удалось создать директории...');
            }
        }
    }

    private function delTree($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    private function check_cach()
    {
        clearstatcache();
        if (stat($this->patch_to_cache_file)['mtime'] - stat($this->tamplate)['mtime'] > $this->cach_differenc_time and file_exists($this->patch_to_cache_file)) {
            return $this->patch_to_cache_file;
        } else {
            return false;
        }
    }

    private function process_template()
    {
        $templ = file_get_contents($this->tamplate);
        $templ = $this->prepare_templ($templ);
        $templ = $this->cut_php_code_in_php_tags($templ);
        $templ = $this->proces_psevdo_php_code($templ);
        $temp = mb_substr($templ, 0, mb_strpos($templ, 'html'));
        $templ = mb_substr($templ, mb_strpos($templ, 'html'));
        $templ = $this->proces_html($templ);
        $templ = $temp . $templ;
        $templ = $this->restore_php_code($templ);
        if (!file_exists($this->cache_dir)) {
            if (!mkdir($this->cache_dir, 0777, true)) {
                die('Failed to create directories...');
            }
        }
        file_put_contents($this->patch_to_cache_file, $templ);
        return $this->patch_to_cache_file;
    }

    private function clear_wrap($str)
    {
        $str = str_replace(array("\r\n", "\r", "\n", "\t"), '', $str);
        return $str;
    }

    private function extract_data_in_brackets($prefix)
    {
        {

            $brackets_count = 0;
            $ctx_in_brackets = false;
            for ($ii = 0; isset($prefix[$ii]); $ii++) {
                if ($prefix[$ii] == '(') {
                    $brackets_count++;
                }
                if ($prefix[$ii] == ')') {
                    $brackets_count--;
                }
                if ($brackets_count > 0) {
                    $ctx_in_brackets .= $prefix[$ii];
                }
            }
            if ($ctx_in_brackets) {
                $ctx_in_brackets = substr($ctx_in_brackets, 1);
            }

            $prefix = str_replace('(' . $ctx_in_brackets . ')', '', $prefix);
            return ['prefix' => $prefix, 'ctx_in_brackets' => $ctx_in_brackets];
        }

    }

    private function extract_param_between_slasch($data)
    {
        $param1 = '';
        $param2 = '';
        if (in_text('/', $data)) {
            $delimiter_detect = false;
            for ($ii = 0; isset($data[$ii]); $ii++) {
                if ($data[$ii] == '/') {
                    $delimiter_detect = true;
                }

                if (!$delimiter_detect) {
                    $param1 .= $data[$ii];
                } else {
                    $param2 .= $data[$ii];
                }

            }
            $param2 = substr($param2, 1);
        } else {
            $param2 = $data;
        }


        return [$param1, $param2];
    }

    private function check_syntaxis($file)
    {
        if (@eval('return true; ?>' . file_get_contents($file))) {
            if (!file_exists($file)) {
                echo 'cach file not exist' . "\n";
            } else {
                echo 'syntaxis error in tamplate' . "\n";
            }
            return false;
        } else {
            return true;
        }
    }

    private function find_php_block_end(&$txt, $pos)
    {
        $start =0;
        $open_start = $pos-1;
        $count_opened=1;
        while ($count_opened>0 or $start==0) {

            $start=1;
            $open = mb_stripos($txt, '{', $open_start + 1);
            $close = mb_stripos($txt, '}', $open_start + 1);
            if ( $open < $close and ($open<>false or $open===0)) {
                $count_opened++;
                $open_start = $open;
            }
            if ($open > $close or $open===false) {
                $count_opened--;
                $open_start = $close;
            }

            if ($count_opened==0 and $start==1){
                $close = $open_start;

            }

        }

        return $close;

    }


}
