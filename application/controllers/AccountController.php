<?php

class ErrorResult
{
    var $code;
    var $error;
}

class RegisterResult
{
    var $createdAt;
    var $objectId;
    var $sessionToken;
}

class AccountController extends Controller
{
    public function register()
    {
        // $username = $_POST['username'];
        // $password = $_POST['password'];

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $username = $request->username;
        $password = $request->password;
        $email = $request->email;

        $register_result = (new AccountModel())->register($username, $password, $email);

        if($register_result == 0)
        {
            $result = ["error" => 'Register failed! Account already exists!'];
            echo json_encode($result);
        }
        else
        {
            echo json_encode($register_result);
        }

        //$this->assign('title', '注册结果');
        //$this->assign('result', $msg);
        //$this->render();
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
