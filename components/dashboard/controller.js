tappyn.controller('dashController', function($scope, dashFactory){
	//on page load grab all
	$scope.type = 'all';
	dashFactory.grabDash($scope.type).success(function(response){
		if(response.http_status_code == 200){
			if(response.success) $scope.dash = response.data;
			else alert(response.message);	 
		}
		else if(response.http_status_code == 500) alert(response.error);
		else $scope.check_code(response.http_status_code);
	});

	$scope.grab_dash = function(type){
		$scope.type = type;

		dashFactory.grabDash(type).success(function(response){
			if(response.success) $scope.dash = response.data;
		});
	}

	$scope.claim_winnings = function(id){
		dashFactory.claimWinnings(id).success(function(response){
			if(response.http_status_code == 200){
				if(response.success) $scope.set_alert(response.message, "default");	
				else $scope.set_alert(response.message, "default");	 
			}
			else if(response.http_status_code == 500) $scope.set_alert(response.error, "error");
			else $scope.check_code(response.http_status_code);
		});
	}
})