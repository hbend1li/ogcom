/**
* Created by hbendali on 31/08/17.
*/

var ogcomApp = angular
  .module('ogcomApp', ['ngRoute', 'ngSanitize'])
  
  .config(function($routeProvider) {
    $routeProvider
    .when("/", {
        templateUrl : "templates/main.html",
        controller : "mainCtrl"
    })
    .when("/main", {
        templateUrl : "templates/main.html",
        controller : "mainCtrl"
    })

    .when('/contacts_list', {
      templateUrl: 'templates/contacts_list.html',
      controller: 'contactsListCtrl'
    })
    .when('/contact/:id', {
      templateUrl: 'templates/contact.html',
      controller: 'contactCtrl'
    })

    .when('/produit_list', {
      templateUrl: 'templates/produit_list.html',
      controller: 'produitListCtrl'
    })
    .when('/produit/:id', {
      templateUrl: 'templates/produit.html',
      controller: 'produitCtrl'
    })

    .when("/about", {
        templateUrl : "templates/about.html"
    })
    .when("/help", {
        templateUrl : "templates/help.html"
    })
    .when("/signin", {
        templateUrl : "templates/signin.html",
        controller : "signinCtrl"
    })
    .when("/404", {
        templateUrl : "templates/err404.html"
    })
    .otherwise("404")
    ;
  })

  .directive('stringToNumber', function() {
    return {
      require: 'ngModel',
      link: function(scope, element, attrs, ngModel) {
        ngModel.$parsers.push(function(value) {
          return '' + value;
        });
        ngModel.$formatters.push(function(value) {
          return parseFloat(value);
        });
      }
    };
  })

  .filter('dateDiff', function () {
    var magicNumber = 1; //(1000 * 60 * 60 * 24);
    return function (toDate0, fromDate0) {
      toDate = new Date(toDate0); 
      fromDate = new Date(fromDate0); 
      if(toDate && fromDate){
        var dayDiff = Math.floor((toDate - fromDate) / magicNumber);
        if (angular.isNumber(dayDiff)){
          //console.log('dayDiff: ' + dayDiff);
          return dayDiff + 1;
        }
      }
    };
  })


  // ==================================================================================================
  .controller('mainCtrl', function ($scope, $http, $sce, $compile, $document) {
    console.log('- Main load');

    $scope.$parent.date = new Date();
    //$scope._login = {email:null,password:null};

    $http.get('json/?nav_menu')
      .then(function(res){
        $scope.nav_menu = res.data;
      })
    ;

    
    $scope.session = function(){
      $http.get('api/?json&session')
        .then(function(res){
          $scope.$parent.user = res.data;
          
          var html = ( res.data === false) ?
              '<div class="item">' + 
              ' <img src="img/transparent.png" style="margin:0;height:26px;width:1px;border:0">' + 
              ' <button class="ui inverted red basic button" onclick="$(\'.ui.login\').transition(\'fly down\')">Sign In</button>' +
              '</div>'
            :
              '<div class="ui olive dropdown item">' + 
              $scope.$parent.user.firstname + ' ' + $scope.$parent.user.lastname + 
              ' <img src="img/transparent.png" data-src="' + $scope.$parent.user.gravatar + '" class="ui circular image" style="margin:0 10px;height:26px;width:26px;border:0">' + 
              ' <div class="menu">' + 
              '  <a class="item" href="#!/profile/@' + $scope.$parent.user.username + '"> <i class="la la-lg la-heart-o"></i>&nbsp;&nbsp; Profile </a>' + 
              '  <a class="item" href="#!/messanger/"> <i class="la la-lg la-comments"></i>&nbsp;&nbsp; Messanger </a>' + 
              '  <a class="item" href="#!/" ng-click="submitLogout()"> <i class="la la-lg la-power-off"></i>&nbsp;&nbsp; Signout </a>' + 
              '  <div class="item active"><h4><i class="la la-lg la-code"></i>&nbsp; with &nbsp;<i class="la la-lg la-heart"></i>&nbsp; by&nbsp; <i class="la la-lg la-coffee"></i></h4></div>' +
              ' </div>' + 
              '</div>'
            ;




          // Recompile Element
          $('#rmenu').html( $compile(angular.element(html))($scope) );

          $scope.reactiveMenu();
          $('img')
            .visibility({
              type       : 'image',
              transition : 'fly down in',
              duration   : 1000
            })
          ;

          // console.log($scope.$parent.user);
        })
      ;
    }

    $scope.reactiveMenu = function(){
      $('.ui.dropdown').dropdown();
    }

    $scope.submitLogin = function(){
      $scope.clicked = !$scope.clicked;
      if ($scope.clicked && $scope._login.$valid){
        //console.log($scope._login);
        //console.log('login_form: ');
        //nsole.log($scope._login);
        var sender = {'email':$scope.email,'password':$scope.password};
        $http.post('api/?json&signin', sender, {headers: { 'Content-Type': 'application/json; charset=utf-8' }})
          .then(function(res){
            $scope.clicked = false;
            if (res.data == true){
              // login OK
              $scope.session();
              $('.ui.login').transition('fly down out');
            }else{
              console.log('Login oh! no');
            }
          })
        ;

      }else{
        //$scope._login.err_msg = "Verifier email et le mot de pass";
        $scope.clicked = false;
      }
    }

    $scope.submitLogout = function(){
      $http.get('api/?json&signout')
        .then(function(res){
          $scope.session();
        })
      ;
    }

    $scope.session();
  })


  // ==================================================================================================
  .controller('produitListCtrl', function ($scope, $http) {
    if (!$scope.$parent.produits_list)
      $http.get('api/?produit=all')
        .then(function(res){
          $scope.$parent.produits_list = res.data;
          console.log("-> ProduitsList");
          console.log($scope.$parent.produits_list);
        })
      ;

  })
  // ==================================================================================================
  .controller('produitCtrl', function ($scope, $http, $routeParams) {
    $scope.produit = {
      id:0,
      ref:null,
      designation:'',
      fournisseur:null,
      stock:0,
      stk_min:0,
      stk_max:0,
      u:null,
      fam:null,
      s_fam:null,
      prx_achat:0,
      prx_vente:0,
      date_fabrication:null,
      date_peremption:null,
      date_fin_guarantie:null,
      depot:'',
      note:''
    };

    $('.ui.dropdown')
      .dropdown()
    ;

    if ($routeParams.id != "0"){
      $http.get('api/?contact='+$scope.contact.id)
        .then(function(res){
          //res.data[0].credit = Number (res.data[0].credit) ;
          $scope.contact = res.data[0];
        })
      ;
    }

    $scope.submit = function(){
      if ($scope.form.$valid)
        console.log($scope.produit);
    }

    $scope.delete = function(){
    }

  })

  // ==================================================================================================
  .controller('contactsListCtrl', function ($scope,$http) {
    if (!$scope.$parent.contacts_list)
      $http.get('api/?contact')
        .then(function(res){
          $scope.$parent.contacts_list = res.data;
        })
      ;
  })
  // ==================================================================================================
  .controller('contactCtrl', function ($scope,$http,$routeParams,$location) {
    $scope.cv = {
      id:"0",
      nom:null,
      contact:[{name:null, type:null, value:null}],
      address:'',
      type_contact:null,
      credit:0,
      note:null
    };

    /*$('.ui.dropdown')
      .dropdown()
    ;*/

    console.log($scope.cv);

    if ($routeParams.id != "0"){
      $http.get('api/?contact='+$scope.contact.id)
        .then(function(res){
          res.data[0].more_contact = angular.fromJson(res.data[0].more_contact);
          res.data[0].credit = Number (res.data[0].credit) ;
          $scope.contact = res.data[0];
          console.log("-> Contact "+$scope.contact.id);
          console.log($scope.contact);
        })
      ;
    }

    $scope.add_number = function(){
      $scope.cv.contact.push({name:null, type:null, value:null});
    }

    
    $scope.submit = function(){

      console.log($scope.cv_form);

      if ( $scope.cv_form.$valid && !$scope.save){
        $scope.save = !$scope.save;
        $scope.cv.contact = angular.toJson($scope.cv.contact);
        console.log($scope.cv);

        $http.post('api/?json&save=contact', $scope.cv, {headers: { 'Content-Type': 'application/json; charset=utf-8' }})
        .then(function (r) {
          console.log(r);

          if (r.data.id != "0")
            $scope.cv.id = r.data.id;

          if (r.data.error[1] == null)
            $scope.msg = "cv enregistrer";
          else
            $scope.msg = r.data.error[2];
          
          console.log("-> done!");
          $scope.save = false;
          delete $scope.$parent.contacts_list;
          $location.path('contacts_list');
        },      
        function(){
          $scope.msg = "Erreur d'enregistrement";
          console.log("-> field");
          $scope.save = false;
        });
      }
    }

    $scope.delete = function(){
      $scope.spiner_del = true;
      if ($scope.contact.id != "0" && $scope.contact.id != "" && confirm("Etre vous sure de vouloir supprimer ce contact ?")){
        
        $http.post('./api/?contact=d&id='+$scope.contact.id)
        .then(function (r) {
          console.log(r);
          if (!r.data.error){
            console.log("-> delete field");
            $location.path('contacts_list');          
          }else if (r.data.error[1] != null){
            console.log("-> delete field");
          }else{
            delete $scope.$parent.contacts_list;
            console.log("-> delete done!");
            $location.path('contacts_list');
          }
        },      
        function(){
          console.log("-> delete field");
        });
        $scope.spiner_del = false;
      }else{
        $scope.spiner_del = false;
      }
    }
    

  })


  // ==================================================================================================
  .controller('signinCtrl', function() {
    $('.ui.login')
      .transition('fly down in')
    ;
  })

  // ==================================================================================================
  .controller('TodoListCtrl', function() {
    var todoList = this;
    todoList.todos = [
      {text:'learn AngularJS', done:true},
      {text:'build an AngularJS app', done:false}];
 
    todoList.addTodo = function() {
      todoList.todos.push({text:todoList.todoText, done:false});
      todoList.todoText = '';
    };
 
    todoList.remaining = function() {
      var count = 0;
      angular.forEach(todoList.todos, function(todo) {
        count += todo.done ? 0 : 1;
      });
      return count;
    };
 
    todoList.archive = function() {
      var oldTodos = todoList.todos;
      todoList.todos = [];
      angular.forEach(oldTodos, function(todo) {
        if (!todo.done) todoList.todos.push(todo);
      });
    };
  })
;


$('img')
  .visibility({
    type       : 'image',
    transition : 'fly down in',
    duration   : 1000
  })
;

$('.dropdown')
  .dropdown({
    on: 'hover',
    transition: 'slide down'
  })
;
