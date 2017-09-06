<?php

// Include FireWorks lib
require_once('../bin/fw.php');
//print_r($_SESSION);
//============================================================================================
//global $_SESSION;

$sql = "";
$err = "";
$ret = new \stdClass;

//============================================================================================
if (isset($_GET['html'])){
  header('Content-Type: text/html; charset=utf-8');

  $ret='';
  switch (isset($_GET['html'])) {
    case 'nav_menu':
      
      //$ret .= '<div style="width: 150px;"></div><img src="img/logo.svg" alt="" height="60px">';
      $ret .= '<a class="item" href="#!/"><i class="home icon"></i>Accueil</a>';
        if ($fw->policy('admin')){
          $ret .= '<div class="ui dropdown item top_menu"> <i class="la s1 la-cubes" ></i> Administrateur <div class="menu">';

            $ret .= '<a class="item" href="#!/user_list"> <i class="la s1 la-users"></i> List des utilisateur</a>';
            $ret .= '<a class="item" href="#!/settings"> <i class="la s1 la-sliders"></i> Paramètres</a>';
            $ret .= '<a class="item" href="bin/phpinfo.php" target="_blank"> <i class="la s1 la-file-code-o"></i> PHP Info()</a>';
          $ret .= '</div> </div>';
        }

        $ret .= '<div class="right menu">';
        if (isset($_GET['signin'])){
          $ret .= '<div class="item">'.$_SESSION["user"]->firstname.' '.$_SESSION["user"]->lastname . '</div>';
        }else{
          $ret .= '<div class="item">'.
            '<input type="text">'.
            '<a href="#!/login" class="ui primary basic button">Signin</a>'.
            '</div>';
        }      
      $ret .= '</div>';
    

      break;
  }

  echo $ret;
}

//============================================================================================
else if (isset($_GET['json'])){
  header('Content-Type: application/json; charset=utf-8');

  if (isset($_GET['signin']))
  {
    $ret = false;
    $get = $fw->get_json(true);
    //print_r($get);
    if ( isset($get->email) && ($get->email != "") && isset($get->password) && ($get->password != "") )
      if ($fw->signin( $get->email, $get->password ) ){
        //$ret = $_SESSION['user'];
        //print_r($_SESSION);
        $ret = true;
      }
    else
      $ret = $fw->signin();
  }

  //==========================================================================================
  else if (isset($_GET['session']))
  {
    $ret = isset($_SESSION['user']) ?  $_SESSION['user'] : false;
  }
  
  //============================================================================================
  else if ($fw->signin())
  {
      
    $dbg = isset($_GET["debug"]) && $fw->policy('debug') ? true : false ;

    //==========================================================================================
    if (isset($_GET['signout']))
    {
      $ret = true;
      $fw->signout();
    }


    //==========================================================================================
    else if (isset($_GET['acl']))
    { 
      $ret = $_GET['acl']!="" ? $fw->policy($_GET['acl']) : false;
    }

    //==========================================================================================
    else if (isset($_GET['list']) || isset($_GET['save']) || isset($_GET['delete']) )
    {

      // save
      if (isset($_GET['save']))
      {
        if( $_GET['save']=='user' || $_GET['save']=='contact' || $_GET['save']=='produit' )
        {
          $form = $fw->get_json(true);
          $id = $form->id;
          unset($form->id);
          $sql = $fw->sql_gen($_GET['save'],$form, $id);
          //$ret = $fw->fetchAll($sql,true,$dbg);
          $ret = $sql;
        }
      }
      
      // delete
      else if ( isset($_GET['delete']) && isset($_GET['id']) && $_GET['id']!='' && $_GET['id']!='0' )
      {
        if( $_GET['delete']=='user' || $_GET['delete']=='contact' || $_GET['delete']=='produit' )
        {
          $where = "id=". $fw->sql_inj($_GET['id']);
          $ret = $fw->fetchAll("DELETE * FROM $_GET[delete] WHERE $where",true,$dbg); 
        }
      }
      
      // view selected id or view all
      else if ( isset($_GET['list']) )
      {
        //echo $_GET['list'];
        //exit;
        if( $_GET['list']=='user' || $_GET['list']=='contact' || $_GET['list']=='produit' )
        {
          $where = ( !isset($_GET['id']) || $_GET['id']=='' || $_GET['id']=='0' ) ? "id is not null" : "id=".$fw->ossl($fw->sql_inj($_GET['id']),'dec') ;
          $ret = $fw->fetchAll("SELECT * FROM $_GET[list] WHERE $where");

          if ($_GET['list']=='user')
            foreach ($ret as $value)
            {
                $value->id = $fw->ossl($value->id, 'enc');
                $value->gravatar = $fw->gravatar($value->email);
                $value->acl = json_decode($value->acl);
                unset($value->password);
            }

        }
      }
    }
  }

  echo json_encode( $ret );
}

//exit();
//============================================================================================




//print_r($_SESSION);