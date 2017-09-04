<!DOCTYPE html>

<html lang="fr" dir="ltr" ng-app="ogcomApp">
<head>
  <meta charset="UTF-8">
  <title>#Open Gestion Commercial</title>
  <link rel="icon" href="img/ogcom.ico"/>

  <!-- JQuery -->
  <script src="js/jquery/jquery-3.2.1.js"></script>
  <script src="js/jquery/jquery.tablesort.min.js"></script>
  <!--script src="js/markdown.min.js"></script-->

  <!--  Angular  -->
  <script src="js/angular/angular.js"></script>
  <script src="js/angular/i18n/angular-locale_fr-fr.js"></script>
  <script src="js/angular/angular-route.js"></script>
  <script src="js/angular/angular-sanitize.js"></script>

  <!--  Semantic-UI  -->
  <link rel="stylesheet" type="text/css" href="css/semantic/semantic.css"/>
  <script src="css/semantic/semantic.js"></script>

  <!--  line-awesome.css  -->
  <!--link rel="stylesheet" href="css/line-awesome.css"/-->
  <link rel="stylesheet" href="css/style.css"/>

	<script src="js/app.js"></script>

  <style type="text/css">
    .login{
      position: fixed;
      top:70px;
      right: 20px;
      width: 300px;
      visibility: hidden;
    }
  </style>

</head>
<body ng-controller="mainCtrl">
	
  <div class="ui top inverted fixed menu" ng-init="nav_menu=''">
    <div class="header item">
      #Open<b>GCOM</b>
    </div>
    <div class="ui dropdown item top_menu" ng-repeat="ele in nav_menu"  ng-init="$last ? reactiveMenu() : angular.noop()">
      <i class="{{ele.icon}} icon"></i> {{ele.item}}
      <div class="menu">
        <a class="item" href="{{sub_ele.url}}" target="{{sub_ele.target}}" ng-repeat="sub_ele in ele.sub_item"> <i class="{{sub_ele.icon}} icon"></i> {{sub_ele.item}}</a>
      </div>
    </div>
    <div class="right menu" id="rmenu">
    </div>
  </div>

	<div ng-model="main" style="margin-top: 62px;" ng-view></div>



  <div class="ui login">
    <form class="ui form" name="_login">
      <div class="ui red secondary segment">
        <div class="field">
          <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="email" name="email" ng-model="email" placeholder="E-mail address" required>
          </div>
        </div>
        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" name="password" ng-model="password" placeholder="Password" minlength="6" required>
          </div>
        </div>
        <button type="submit" class="ui fluid submit button" ng-class="{'loading': clicked}" ng-click="submitLogin(this)">Login</button>
      </div>
      <div class="ui error message">{{_login.err_msg}}</div>
    </form>
  </div>


</body>
</html>