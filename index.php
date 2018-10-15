<!DOCTYPE html>

<html lang="fr" dir="ltr" ng-app="ogcomApp">
<head>
  <meta charset="UTF-8">
  <title>#Open Gestion Commercial</title>
  <link rel="icon" href="img/ogcom.ico"/>

  <!-- JS -->
  <script src="vendor/jquery/jquery-3.3.1.min.js"></script>
  <script src="vendor/angular/angular.js"></script>
  <script src="vendor/angular/i18n/angular-locale_fr-fr.js"></script>
  <script src="vendor/angular/angular-route.js"></script>
  <script src="vendor/angular/angular-sanitize.js"></script>
  <script src="vendor/semantic-ui/semantic.min.js"></script>
	<script src="js/app.js"></script>

  <!--  CSS  -->
  <link rel="stylesheet" href="vendor/semantic-ui/semantic.min.css">
  <link rel="stylesheet" href="vendor/line-awesome/line-awesome.css"/>
  <link rel="stylesheet" href="css/defaults.css"/>

</head>
<body ng-controller="mainCtrl">
	
  <div class="ui top inverted fixed menu" ng-init="nav_menu=''">
    <a class="ui medium header item" href="#">
      #Open<b>GCOM</b>
    </a>
    <div class="ui dropdown item top_menu" ng-repeat="ele in nav_menu"  ng-init="$last ? reactiveMenu() : angular.noop()">
      <i class="la la-2x la-{{ele.icon}}"></i>&nbsp;&nbsp; {{ele.item}}
      <div class="menu">
        <a class="item" href="{{sub_ele.url}}" target="{{sub_ele.target}}" ng-repeat="sub_ele in ele.sub_item"> <i class="la la-lg la-{{sub_ele.icon}}"></i>&nbsp;&nbsp; {{sub_ele.item}}</a>
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