<?php
class DatabaseAccessObject {
    private $mysql_address = "";
    private $mysql_username = "";
    private $mysql_password = "";
    private $mysql_database = "";
    private $db;
    private $last_sql = "";
    private $last_id = 0;
    private $last_num_rows = 0;
    private $error_message = "";

    /**
     * 這段是『建構式』會在物件被 new 時自動執行，裡面主要是建立跟資料庫的連接，並設定語系是萬國語言以支援中文
     */
    public function __construct($mysql_address, $mysql_username, $mysql_password, $mysql_database) {
        $this->mysql_address  = $mysql_address;
        $this->mysql_username = $mysql_username;
        $this->mysql_password = $mysql_password;
        $this->mysql_database = $mysql_database;

        try {
            $db = new PDO("mysql:host=".$this->mysql_address.";charset=utf8mb4;dbname=".$this->mysql_database, $this->mysql_username, $this->mysql_password);
            //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);//Suggested to uncomment on production websites
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Suggested to comment on production websites
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            $this->db = $db;
        
        } catch(PDOException $e) {
            //show error
            echo '<p class="bg-danger">'.$e->getMessage().'</p>';
            exit;
        } 
    }

    /**
     * 這段是『解構式』會在物件被 unset 時自動執行，裡面那行指令是切斷跟資料庫的連接
     */
    public function __destruct() {
        $this->db = null;
    }

    /**
     * 這段用來執行 MYSQL 資料庫的語法，可以靈活使用
     */
    public function execute($sql = null, $data_array) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($data_array);
            return $stmt->fetchAll(); 
		} catch(PDOException $e) {
		    $this->error_message = '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
    }

    /**
     * 這段用來讀取資料庫中的資料，回傳的是陣列資料
     */
    public function query($table, $condition, $order_by, $fields, $limit, $data_array){
        if(!isset($data_array) OR count($data_array) == 0)return false;
        if(empty($table))return false;
        if(is_numeric($limit))$limit = "LIMIT ".$limit;
        if(empty($condition))$condition = 1;
        if(empty($order_by))$order_by = 1;
        if(empty($fields))$fields = "*";
        $this->last_sql = "SELECT {$fields} FROM {$table} WHERE {$condition} ORDER BY {$order_by} {$limit}";
        try {
			$stmt = $this->db->prepare($this->last_sql);
			$stmt->execute($data_array);
            return $stmt->fetchAll();
		} catch(PDOException $e) {
		    $this->error_message = '<p class="bg-danger">'.$e->getMessage().'</p>';
        }
    }

    /**
     * 這段可以新增資料庫中的資料，並把最後一筆的 ID 存到變數中，可以用 getLastId() 取出
     */
    public function insert($table = null, $data_array = array()) {
        if($table===null)return false;
        if(count($data_array) == 0) return false;

        $tmp_col = array();
        $tmp_dat = array();

        foreach ($data_array as $key => $value) {
            $tmp_col[] = $key;
            $tmp_dat[] = ":$key";
            $prepare_array[":".$key] = $value;
        }
        $columns = join(", ", $tmp_col);
        $data = join(", ", $tmp_dat);

        $this->last_sql = "INSERT INTO " . $table . "(" . $columns . ")VALUES(" . $data . ")";
        
        $stmt = $this->db->prepare($this->last_sql);
        $stmt->execute($prepare_array);
        $this->last_id = $this->db->lastInsertId();
    }

    /**
     * 這段可以更新資料庫中的資料
     */
    public function update($table = null, $data_array = null, $key_column = null, $id = null) {
        if($table == null)return false;
        if($id == null) return false;
        if($key_column == null) return false;
        if(count($data_array) == 0) return false;

        $setting_list = "";
        for ($xx = 0; $xx < count($data_array); $xx++) {
            list($key, $value) = each($data_array);
            $setting_list .= $key . "=" . ':'.$key;
            if ($xx != count($data_array) - 1){
                $setting_list .= ",";
            }
        }
        $data_array[$key_column] = $id;
        $this->last_sql = "UPDATE " . $table . " SET " . $setting_list . " WHERE " . $key_column . " = " . ":".$key_column;
        $stmt = $this->db->prepare($this->last_sql);                       
        $stmt->execute($data_array);
    }
    /**
     * 這段可以刪除資料庫中的資料
     */
    public function delete($table = null, $key_column = null, $id = null) {
        if ($table===null) return false;
        if($id===null) return false;
        if($key_column===null) return false;

        $this->last_sql = "DELETE FROM $table WHERE " . $key_column . " = " . ':'.$key_column;
        $stmt = $this->db->prepare($this->last_sql);
        $stmt->execute(array( ':'.$key_column => $id));
    }

    /**
     * @return string
     * 這段會把最後執行的語法回傳給你
     */
    public function getLastSql() {
        return $this->last_sql;
    }

    /**
     * @param string $last_sql
     * 這段是把執行的語法存到變數裡，設定成 private 只有內部可以使用，外部無法呼叫
     */
    private function setLastSql($last_sql) {
        $this->last_sql = $last_sql;
    }

    /**
     * @return int
     * 主要功能是把新增的 ID 傳到物件外面
     */
    public function getLastId() {
        return $this->last_id;
    }

    /**
     * @param int $last_id
     * 把這個 $last_id 存到物件內的變數
     */
    private function setLastId($last_id) {
        $this->last_id = $last_id;
    }

    /**
     * @return int
     */
    public function getLastNumRows() {
        return $this->last_num_rows;
    }

    /**
     * @param int $last_num_rows
     */
    private function setLastNumRows($last_num_rows) {
        $this->last_num_rows = $last_num_rows;
    }

    /**
     * @return string
     * 取出物件內的錯誤訊息
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }

    /**
     * @param string $error_message
     * 記下錯誤訊息到物件變數內
     */
    private function setErrorMessage($error_message)
    {
        $this->error_message = $error_message;
    }
}
