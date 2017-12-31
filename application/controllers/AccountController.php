<?php

class AccountController extends Controller
{
    public function register()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $register_result = (new AccountModel())->register($username, $password);

        if($register_result == 0)
        {
            $msg = 'Register failed! Account already exists!';
        }
        else if($register_result == 1)
        {
            $msg = 'Register successfully!';
        }

        //$this->assign('title', '注册结果');
        //$this->assign('result', $msg);
        //$this->render();

        $msg_json = json_encode($msg);
        echo $msg_json;
    }

    public function login()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $login_result = (new AccountModel())->login($username, $password);

        if($login_result == 0)
        {
            $msg = 'Login failed!Username or password invalid. ';
        }
        else if($login_result == 1)
        {
            $msg = 'Login successfully!';
        }
        $msg_json = json_encode($msg);
        echo $msg_json;
    }

    public function index()
    {
        $this->assign('title', '首页');
        $this->render();
    }
}
