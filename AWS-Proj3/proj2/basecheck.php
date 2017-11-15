<?php
include "openconn.php";

#funcao que verifica se o username existe.
function UsernameCheck($username){
	global $conn;
	$query = "select nick from utilizadores where nick = '$username'";
	$result = mysqli_query($conn, $query) or die("Error ". $query);
	$num_row = mysqli_num_rows($result);
	mysql_close($conn);
	if ($num_row == 0){
		return True;
	} 
	else{
		return False;
	};	
}

function EmailCheck($email){
	global $conn;
	$query = "select email from utilizadores where email = '$email'";
	$result = mysqli_query($conn, $query) or die("Error ". $query);
	$num_row = mysqli_num_rows($result);
	mysql_close($conn);
	if ($num_row == 0){
		return True;
	} 
	else{
		return False;
	}
}

function PasswordChecker($password, $confirmPassword){
	if ($password == $confirmPassword){
		return True;
	}
	else{
		return False;
	}
}


function BirthdayCheck($day, $month, $year){
	$nonLeapYear = array("01"=>31, "02"=>28, "03"=>31, "04"=>30, "05"=>31, "06"=>30, "07"=>31, "08"=>31, "09"=>30, "10"=>31, "11"=>30, "12"=>31);
	$LeapYear = array("01"=>31, "02"=>29, "03"=>31, "04"=>30, "05"=>31, "06"=>30, "07"=>31, "08"=>31, "09"=>30, "10"=>31, "11"=>30, "12"=>31);

	$isLeap = None;
	$year = (int)$year;
	$day = (int)$day;
	if ($year%4 == 0){
		if($year%100 == 0){
			if($year%400 == 0){
				$isLeap = True;
			}
			else{
				$isLeap = False;
			}
		}
		else{
			$isLeap = True;
		}
	}
	else{
		$isLeap = False;
	}

	if ($isLeap){
		if ($day <= $LeapYear[$month]){
			return True;
		}
		else{
			return False;
		}
	}
	else{
		if ($day <= $nonLeapYear[$month]){
			return True;
		}
		else{
			return False;
		}	
	}
}
?>