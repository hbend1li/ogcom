<?php
//date_default_timezone_set('UTC');
date_default_timezone_set('Africa/Algiers');
setlocale(LC_MONETARY, 'dz_DZA');

error_reporting(E_ALL);
ini_set("display_errors", 1);
//ini_set('session.cookie_domain', '.ogcom.com' );

//session_cache_limiter('nocache');
//session_cache_expire(10);
session_start();


// Prepart connexion à la base de données
// FireWorks ('host|username|password|bdd');
$fw = new FireWorks(__CONNECTION__);

//$fw->telegram_api = "156659332:AAFCyXi94dL02gXaHlzRGw7Mk9WZsfMMN1A";
//$fw->telegram_id  = "127969204";

class FireWorks{

    private static $databases;
    private $connection;
    public $tb_user      = "user";
    public $tb_log       = "log";
    public $telegram_api;
    public $telegram_id;


    public function __construct($connDetails){
        if(!is_object(self::$databases[$connDetails])){
            list($host, $port, $user, $pass, $dbname) = explode('|', $connDetails);
            $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
            self::$databases[$connDetails] = new PDO($dsn, $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        }
        $this->connection = self::$databases[$connDetails];
    }

    // RUN SQL =========================================================
    public function fetchAll($sql,$result = null,$debug = null){
        $args = func_get_args();
        array_shift($args);
        $statement = $this->connection->prepare($sql);
        $statement->execute($args);

        if ($result){
            $result = array();
            $result['sqlState']  = $statement->errorInfo()[0];
            $result['errorCode'] = $statement->errorInfo()[1];
            $result['message']   = $statement->errorInfo()[2];
            $result['id']        = $this->connection->lastInsertId();
            $result['result']    = $statement->fetchAll(PDO::FETCH_OBJ);

            if ($this->policy('debug'))
             $result['sqlquery'] = $sql;

            return $result;
        }else
            return $statement->fetchAll(PDO::FETCH_OBJ);
    }


    // SQL Generate INSERT or UPDATE ===================================
    public function sql_gen($table, $fields = array(), $id="")
    {
        if ( strtoupper($id) =="SELECT"){ // SELECT x,x,x,x,
            $keys = "";
            foreach ( $fields as $key )
            {
                $keys .= "`$key`,";
            }
            $keys = substr($keys,0,-1);
            return "SELECT $keys FROM $table WHERE `id`='$id'";

        }else if (intval($id)==0){ // INSERT
            $keys = "";
            $vals = "";
            foreach ( $fields as $key => $val )
            {
                $keys .= "`". $this->sql_inj($key) ."`,";
                $vals .= $this->reformat_date($key,$val).",";
            }
            $keys = substr($keys,0,-1);
            $vals = substr($vals,0,-1);
            return "INSERT INTO $table ($keys) VALUES ($vals)";

        }else{ // UPDATE
            $element = "";
            foreach ( $fields as $key => $val )
            {
                $element .= "`". $this->sql_inj($key) ."`=". $this->reformat_date($key,$val) .",";
            }
            $element = substr($element,0,-1);
            return "UPDATE $table SET $element WHERE `id`='$id'";
        }
    }

    // Reformat value if field is date =================================
    public function reformat_date($key, $val)
    {
        if (substr($key,0,5) =="date_")
        {
            $val = "'".date("Y-m-d", strtotime( substr($val, 0, strpos($val, '('))))."'";
            if ($val=="'1970-01-01'") $val="null";
        }else{
            $val = "'".$this->sql_inj($val)."'";
        }
        return $val;
    }

    // TELEGRAM ========================================================
    public function telegram($message)
    {
        if ($telegram_api != "" && $telegram_id != "")
        {
            $message = htmlentities($message);
            $result = file_get_contents("https://api.telegram.org/bot$telegram_api/sendMessage?chat_id=$telegram_id&text=$message");
            //$result = json_decode($result, true);
        }
    }

    // AVATAR ==========================================================
    public function gravatar( $email, $img = false, $s = 80, $d = 'mm', $r = 'g', $atts = array() ) {
        //

        $url = 'https://www.gravatar.com/avatar/' . md5( strtolower( trim( $email ) ) );
        //$url = './img/mm.png';

        if ( $img ) {
            $url .= "?s=$s&d=$d&r=$r";

            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }


        return $url;
    }

    // ACCESS =========================================================
    public function policy($acl) {
        global $_SESSION;
        return (isset($_SESSION['user']) && isset($_SESSION['user']->policy->$acl) )?$_SESSION['user']->policy->$acl:false;
    }



    function ossl( $string, $action = 'enc' ) {
        // you may change these values to your own
        global $_SESSION;
     
        $output = "";
        $pass = $_SESSION['user']->uuid;
        $method = 'aes128';
        $iv = "1827364554637281";
        
        if ( $action == 'enc' ) {
            $result = openssl_encrypt ($string, $method, $pass, false, $iv);
        } else if( $action == 'dec' ) {
            $result = openssl_decrypt($string, $method, $pass, false, $iv);
        }
        return $result;
    }

}





/////////////////////////////////////////////////////////////////////

// Get JSON from tmp ===============================================
function json_get($decode = false)
{
  $putjson = fopen("php://input", "r");
  $json = "";
  while (!feof($putjson)){
    $json .=fgets($putjson);
  }
  fclose($putjson);
  if (!$decode){
    return $json;
  }else{
    return json_decode($json);
  }
}

// SQL Injection ===================================================
function sql_inj($value)
{
  $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
  $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");
  return str_replace($search, $replace, $value);
}

// LOG =============================================================
function logs($msg)
{
  global $_SESSION;
  $date = date("Y-m-d H:i:s");
  $user = isset($_SESSION['user']) ? $_SESSION['user']->username : "";
  $ip   = $_SERVER['REMOTE_ADDR'];
  
  $mylog = fopen("../logs.txt", "a") or die();
  fwrite($mylog, "\n$date | $user | $ip | $msg");
  fclose($mylog);
}

function signin()
{
  global $fw, $_SESSION;
  if (isset($_SESSION['user'])){
    $uuid = $_SESSION['user']->uuid;
    $fw->fetchAll("UPDATE user SET `updated`=NOW() WHERE `uuid`='$uuid'");
    return true;
  }else{
    return false;
  }
}