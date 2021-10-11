var app = angular.module('app', []);

console.log('Angular App');

const BASE_URL = "http://localhost/PDS/www/";
//const BASE_URL = "http://smdtech.softether.net:65000/";

app.controller('notesCtrl', function($scope, $http, $location) {
    
    $scope.saveNote = function (n, i) {

        $http.get(`${BASE_URL}/services/note/create.php?text=${n}&tid=${i}`).then(function(e) {
            console.log(e.data);
            alert(`New notes ${i} saved`);
        });
        
    }

});

app.controller('getNotesCtrl', function($scope, $http) {
    
    $scope.notesClass = "col-sm-11";
    $scope.addNoteClasss = "col-sm-1";

    $scope.loadNotes = function (i) {
        
        console.log('id', i);

        $scope.note = '';
        $scope.notes = [];
        $scope.rows = 0;
        
        $http.get(`${BASE_URL}/services/note/read.php?tid=${i}`).then(function(e) {
            console.log(e.data.results.list[0].text);
            $scope.note = e.data.results.list[0].text;
            $scope.notes = e.data.results.list;
            $scope.rows = e.data.results.rows;
            console.log($scope.rows);
        });        
    }

    $scope.newNoteSection = function() {
        $scope.notesClass = !"col-sm-6";
        $scope.addNoteClasss = !"col-sm-6";
    }

});