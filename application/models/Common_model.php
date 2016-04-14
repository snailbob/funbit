<?php 

class Common_model extends CI_Model

{

	public function __construct() {

		parent::__construct();

	}

	
	
	public function days_of_week(){
		$arr = array(
			'Sunday',
			'Monday',
			'Tuesday',
			'Wednesday',
			'Thursday',
			'Friday',
			'Saturday'
		);
		
		return $arr;
	
	}
	
public function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2) {
		// Calculate the distance in degrees
		$point1_lat = floatval($point1_lat);
		$point1_long = floatval($point1_long);
		$point2_lat = floatval($point2_lat);
		$point2_long = floatval($point2_long);
		
		$degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));
	 
		// Convert the distance in degrees to the chosen unit (kilometres, miles or nautical miles)
		switch($unit) {
			case 'km':
				$distance = $degrees * 111.13384; // 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)
				break;
			case 'mi':
				$distance = $degrees * 69.05482; // 1 degree = 69.05482 miles, based on the average diameter of the Earth (7,913.1 miles)
				break;
			case 'nmi':
				$distance =  $degrees * 59.97662; // 1 degree = 59.97662 nautic miles, based on the average diameter of the Earth (6,876.3 nautical miles)
		}
		return round($distance, $decimals);
	} 	
	
	
	public function get_message($name = NULL){
		$message['id'] = '';
		$message['name'] = $name;
		$message['success'] = '<i class="fa fa-check-circle text-success"></i> Action successful.';
		$message['error'] = '<i class="fa fa-warning text-info"></i> Action failed to execute.';
		$message['info'] = '<i class="fa fa-info-circle"></i> No issue found.';
		$message['confirm'] = '<i class="fa fa-question-circle text-info"></i> Confirm action.';
		
		if($name != NULL){
			$mess = $this->master->getRecords('validation_messages', array('name'=>$name));
			
			if(count($mess) > 0){
				$message['id'] = $mess[0]['id'];
				$message['success'] = '<i class="fa fa-check-circle text-success"></i> '.$mess[0]['success_message'];
				
				if($mess[0]['error_message'] != ''){
					$message['error'] = '<i class="fa fa-warning  text-warning"></i> '.$mess[0]['error_message'];
				}
				
				if($mess[0]['info_message'] != ''){
					$message['info'] = '<i class="fa fa-info-circle  text-info"></i> '.$mess[0]['info_message'];
				}
				
				if($mess[0]['confirm_message'] != ''){
					$message['confirm'] = '<i class="fa fa-question-circle text-info"></i> '.$mess[0]['confirm_message'];
				}
			}

			return $message;

		}
		else{
			$messages = array();
			$mess = $this->master->getRecords('validation_messages');
			$error = array();
			$success = array();
			$gloval = array();
			foreach($mess as $r=>$value){
				$confirm_fa = '<i class="fa fa-question-circle text-info"></i> ';
				$success_fa = '<i class="fa fa-check-circle text-success"></i> ';
				$err_fa = '<i class="fa fa-info-circle text-info"></i> ';
				$gloval[$value['name']] = $confirm_fa.$value['confirm_message'];
				$error[$value['name']] = $err_fa.$value['error_message'];
				$success[$value['name']] = $success_fa.$value['success_message'];
			}
			$messages['success'] = $success;
			$messages['error'] = $error;
			$messages['global'] = $gloval;
			return $messages;
			
		}
	}
		
	
	public function colors(){
		$arr = array(
			'bg-choco',
			'bg-lavander',
			'bg-maroon',
			'bg-deep',
			'bg-grass',
			'bg-blood'
		);
		
		return $arr;
		
	}
	
	public function avatar($id, $type = 'agency'){
	
		if($type == 'agency'){
			$info = $this->master->getRecords('agency',array('id'=>$id));
			$default = base_url().'uploads/avatars/agency.jpg';
		}
	
		else if($type == 'customer'){
			$info = $this->master->getRecords('customers',array('id'=>$id));
			$default = base_url().'uploads/avatars/download.jpg';
		}
		else if($type == 'item'){
			$info = $this->master->getRecords('menu_items',array('id'=>$id));
			$default = base_url().'uploads/avatars/menu-placeholder.jpg';
		}
		else{
			$info = $this->master->getRecords('students',array('id'=>$id));
			$default = base_url().'uploads/avatars/download.jpg';
		}
		
		if(count($info) > 0){
			
			if($info[0]['avatar'] != ''){ //check if not default
				if($type == 'item'){
					$default = base_url().'uploads/menuitm/'.$info[0]['avatar'];
				}
				else{
					$default = base_url().'uploads/avatars/'.$info[0]['avatar'];
				}
				
			}

		}// not existing user
		
		return $default;
		
	}

	public function db_id_field($db, $id, $field, $idfield = 'id'){
		$data = $this->master->getRecords($db, array($idfield=>$id));
		$name = 'Not specified';
		if(count($data) > 0){
			$name = $data[0][$field];
		}
		return $name;
	}
	
	public function admin_email(){
		$admin = $this->master->getRecords('admin');
		return $admin[0]['email'];
	}
	

	
}

?>