<?php

class Marriagnet
{
    protected $_config = [];

    public function __construct($_config)
    {
        $this->_config = $_config;
    }
    
    public function run()
    {
        spl_autoload_register(array($this, 'loadClass'));
        $this->setReporting();
        $this->removeMagicQuotes();
        $this->unregisterGlobals();
        $this->setDbConfig();
        $this->route();
    }

    public function route()
    {
        $controllerName = $this->_config['defaultController'];
        $actionName = $this->_config['defaultAction'];
        $param = array();

        $url = $_SERVER['REQUEST_URI'];
        $position = strpos($url, '?');
        $url = $position == false ? $url : substr($url, 0, $position);
        $url = trim($url, '/');

        if($url){
            $urlArray = explode('/', $url);
            $urlArray = array_filter($urlArray);

            $controllerName = ucfirst($urlArray[0]);
            
            array_shift($urlArray);
            $actionName = $urlArray ? $urlArray[0] : $actionName;

            array_shift($urlArray);
            $param = $urlArray ? $urlArray : array();
        }

        $controller = $controllerName.'Controller';

        if(!class_exists($controller)){
            exit($controller.' controller not exists.');
        }
        if(!method_exists($controller, $actionName)){
            exit($actionName.' action not exist.'.$controller.' controller.');
        }

        $dispatch = new $controller($controllerName, $actionName);

        call_user_func_array(array($dispatch, $actionName), $param);

    }

    public function setReporting()
    {
        if (APP_DEBUG === true){
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors', 'Off');
            ini_set('log_errors', 'On');
        }
    }
    
    public function stripSlashesDeep($value)
    {
        $value = is_array($value) ? array_map(array($this, 'stripSlashesDeep'), $value) : stripslashes($value);

        return $value;
    }

    public function removeMagicQuotes()
    {
        if (get_magic_quotes_gpc()){
            $_GET = isset($_GET) ? $this->stripSlashesDeep($_GET) : '';
            $_POST = isset($_POST) ? $this->stripSlashesDeep($_POST) : '';
            $_COOKIE = isset($_COOKIE) ? $this->stripSlashesDeep($_COOKIE) : '';
            $_SESSION = isset($_SESSION) ? $this->stripSlashesDeep($_SESSION) : '';
        }
    }

    public function unregisterGlobals()
    {
        if (ini_get('register_globals')) {
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $var) {
                    if ($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }

    // 配置数据库信息
    public function setDbConfig()
    {
        if ($this->_config['db']) {
            define('DB_HOST', $this->_config['db']['host']);
            define('DB_NAME', $this->_config['db']['dbname']);
            define('DB_USER', $this->_config['db']['username']);
            define('DB_PASS', $this->_config['db']['password']);
        }
    }

    // 自动加载控制器和模型类 
    public static function loadClass($class)
    {
        $frameworks = __DIR__ . '/' . $class . '.php';
        $controllers = APP_PATH . 'application/controllers/' . $class . '.php';
        $models = APP_PATH . 'application/models/' . $class . '.php';

        if (file_exists($frameworks)) {
            // 加载框架核心类
            include $frameworks;
        } elseif (file_exists($controllers)) {
            // 加载应用控制器类
            include $controllers;
        } elseif (file_exists($models)) {
            //加载应用模型类
            include $models;
        } else {
            // 错误代码
        }
    }

}
