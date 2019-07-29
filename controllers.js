app.controller("ejercicio1", function($scope, $http) {
    $http.get('http://localhost/ejercicios/apirest/ejercicios/ejercicio1').
    then(function(data) {
        $scope.allData = data.data;
    });


});


app.controller("ejercicio2", function($scope, $http) {

    $scope.traerFotos = traerFotos;
    function traerFotos(page) {

        $http.get('http://localhost/ejercicios/apirest/ejercicios/ejercicio2/' + page).
        then(function(data) {
            $scope.fotos = data.data;
        });
    }

});
