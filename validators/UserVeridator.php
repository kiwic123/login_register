<?php
/**
 * 耦合使用 Database 物件進行資料庫驗證 username 與 email 是否已存在於資料庫
 */
class UserVeridator {

    private $error;

    /**
     * 驗證是否已登入
     */
    public static function isLogin($username){
        if($username != ''){
            return true; 
        } else{
            return false; 
        }
    }

    /**
     * 可取出錯誤訊息字串陣列
     */
    public function getErrorArray(){
        return $this->error;
    }

    /**
     * 驗證二次密碼輸入是否相符
     */
    public function isPasswordMatch($password, $passwrodConfirm){
		if ($password != $passwrodConfirm){
            $this->error[] = 'Passwords do not match.';
            return false;
        }
		return true;
    }

    /**
     * 驗證帳號密碼是否正確可登入
     */
    public function loginVerification($username, $password){
        $result = Database::get()->execute('SELECT * FROM members WHERE active = "Yes" AND username = :username', array(':username' => $username));
        if(isset($result[0]['memberID']) and !empty($result[0]['memberID'])) {
            $passwordObject = new Password();
            if($passwordObject->password_verify($password,$result[0]['password'])){
                return true;
            }
        }
        $this->error[] = 'Wrong username or password or your account has not been activated.';
        return false;
    }

    /**
     * 驗證帳號是否已存在於資料庫中
     */
    public function isUsernameDuplicate($username){
        $result = Database::get()->execute('SELECT username FROM members WHERE username = :username', array(':username' => $username));
        if(isset($result[0]['username']) and !empty($result[0]['username'])){
          $this->error[] = 'Username provided is already in use.';
          return false;
        }
		return true;
    }

    /**
     * 驗證此帳號 ID 跟 開通碼 hash 是否已存在於資料庫中
     */
    public function isReady2Active($id, $active){
        $result = Database::get()->execute('SELECT username FROM members WHERE memberID = :memberID AND active = :active', array(':memberID' => $id, ':active' => $active));
        if(isset($result[0]['username']) and !empty($result[0]['username'])){
          return true;
        }else{
          $this->error[] = 'Username provided is already in use.';
          return false;
        }
    }

    /**
     * 驗證信箱是否已存在於資料庫中
     */
    public function isEmailDuplicate($email){
        $result = Database::get()->execute('SELECT email FROM members WHERE email = :email', array(':email' => $email));
        if(isset($result[0]['email']) AND !empty($result[0]['email'])){
            $this->error[] = 'Email provided is already in use.';
            return true;
        }
		return false;
    }
    
    
}