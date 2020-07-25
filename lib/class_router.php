<?php

class Router
{
    public
        $patch = '',
        $patch_to_controller = '',
        $controllers_dir,
        $controller_404 = '',
        $file = '',
        $parameters,
        $url,
        $url_without_parameters,
        $url_parts,
        $error,
        $auto_create_controller = 0;



    function run()
    {
        $this->extract_controller_and_action();
        $this->get_patch_to_controller();

        if(!empty($this->error)){

            if(!empty($this->controller_404)){
                $this->patch_to_controller = $this->controller_404;
            }else{
                echobr($this->error);
                exit();
            }
        }

    }

    //разделение урл и опредилениее контроллера и экшона
    private function extract_controller_and_action()
    {
        $this->url = $_SERVER["REQUEST_URI"];
        $this->url_without_parameters = explode('?', $_SERVER["REQUEST_URI"])[0];
        @$uri = reset(explode('?', $_SERVER["REQUEST_URI"]));
        $requestedUrl = urldecode(rtrim($uri, '/'));
        $requestedUrl = preg_split('/\//', $requestedUrl, -1, PREG_SPLIT_NO_EMPTY);

        $this->url_parts = $requestedUrl;


        if (isset($requestedUrl[0])) {
            $this->get_patch_and_parameters($requestedUrl);

            if (empty($this->file)) {
                $this->error = "controller_not_found";
            }

        } else {
            $this->patch = '';
            $this->file = 'index.php';
        }
    }

    private function get_patch_and_parameters($requestedUrl)
    {
        if (!empty($requestedUrl)) {
            if (file_exists($this->controllers_dir . $this->patch . '/' . $requestedUrl[0])) {
                $this->patch .= '/' . $requestedUrl[0];
                array_shift($requestedUrl);
                $this->get_patch_and_parameters($requestedUrl);
            } else {
                if (empty($this->patch)) {
                    $this->patch = '/';
                    $this->get_patch_and_parameters($requestedUrl);
                }
                if (empty($this->file) and file_exists($this->controllers_dir . $this->patch . '/' . $requestedUrl[0] . '.php')) {
                    $this->file = '/' . $requestedUrl[0] . '.php';
                    array_shift($requestedUrl);
                    $this->parameters = $requestedUrl;
                } else {
                    array_shift($requestedUrl);
                    $this->parameters = $requestedUrl;
                }

            }
        } else {
            if (empty($this->file)) {
                if (file_exists($this->controllers_dir . $this->patch . '/index.php')) {
                    $this->file = "index.php";
                } else {
                    $this->error = "not_found";
                }
            }
        }
    }

    private function get_patch_to_controller()
    {
        $this->patch_to_controller = $this->controllers_dir . $this->patch . '/' . $this->file;
    }



    private function show_error()
    {
        echo($this->error);
    }
}

