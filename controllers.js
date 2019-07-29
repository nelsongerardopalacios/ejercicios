/*angular.module('EjerciciosApp.controllers', []).
controller('Datos', function($scope, $http) {
            //$http.get('http://localhost/ejercicios/apirest/ejercicios/datos').
            $http.get('https://reqres.in/api/users').
            success(function(data) {
                $scope.allUsers = data;
                console.log(data);
            });
        }

       function Ejercicio1($scope, $http) {
                 $http.get('http://localhost/ejercicios/apirest/ejercicios/ejercicio1').
                 success(function(data) {
                     $scope.usersEx1 = data;
                 });
             }
    
             function Ejercicio2($scope, $http) {
                 $http.get('http://localhost/ejercicios/apirest/ejercicios/ejercicio2').
                 success(function(data) {
                     $scope.usersEx2 = data;
                 });
             }*/



app.controller("ejercicio1", function($scope, $http) {
    //$http.get('https://reqres.in/api/users').
    //$http.get('http://localhost/ejercicios/apirest/ejercicios/getAll').
    $http.get('http://localhost/ejercicios/apirest/ejercicios/ejercicio1').
    then(function(data) {
        $scope.allData = data.data;
        //console.log('AllData: ' + JSON.stringify($scope.allData));
        //console.log($scope.allData);
        //console.log('Data: ' + JSON.stringify($scope.allData[1].data));


    });


});


app.controller("ejercicio2", function($scope, $http) {

    //alert('Mikima Controller');
    $scope.traerFotos = traerFotos;
    // esto es una buena practicasdgsdgsdgsdgsdfgfsd
    //el parametro pagina como mierda queda ahroa??como estaba
    //porque con $ page??
    //porq soy php developer, osea un boludo ahi me gusto a vwer
    //paraaaa

    function traerFotos(page) {

        $http.get('http://localhost/ejercicios/apirest/ejercicios/ejercicio2/' + page).
        then(function(data) {
            $scope.fotos = data.data;
            //console.log($scope.fotos);
        });
    }

});
/*app.controller("ejercicio2", function($scope, $http, $page) {
    
    $http.get('http://localhost/ejercicios/apirest/ejercicios/ejercicio2', $page).
    then(function(data) {
        $scope.allUsers = data;
        console.log(data);

    });
});*/