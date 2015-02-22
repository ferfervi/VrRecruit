angular.module('taskConfirmationApp',  ['ui.router', 'ngResource'])
.config(function($stateProvider, $urlRouterProvider, $locationProvider) {
    // Use hashtags in URL
    $locationProvider.html5Mode(false);

    $urlRouterProvider.otherwise("/");
    $stateProvider
    .state('index', {
      url: "/",
      templateUrl: "/taskConfirmationApp/templates/index.html",
      controller: 'TaskCtrl'
    });
})



.factory('Task', function($resource) {
    return $resource('/task/:id?format=json',
        {id:'@id'},
        {
            'get': {method:'GET'},
            'save': {method: 'PUT'},
            'create': {method: 'POST'},
            'query':  {method:'GET', isArray:true},
            'remove': {method:'DELETE'},
            'delete': {method:'DELETE'}
        }
    );
})


.controller('TaskCtrl', function($scope, $http,Task) {
    $scope.tasks = Task.query();
    
        //define function called from view when user want to retrieve events/logs for a task
	$scope.getEvents = function(id)
	{
		$scope.selected_task=id;
		 // debug: alert(id);

		    $http({
			    url: '/log/index', 
			    method: "GET",
			    params: { task_id: id,
			              format:'json'
			            }
			 })
		          .success(function(data, status, headers, config) 
			   { // this callback will be called asynchronously when the response is available 
			        $scope.logs=data;
			        $scope.have_events=true;
			   })
			  . error(function(data, status, headers, config) 
			   { // called asynchronously if an error occurs  or server returns response with an error status. 
			     	     // alert('e');
			     	     $scope.have_events=false;
			    });
	 };
    


});




