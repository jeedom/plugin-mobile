function mobile_update(){
    	foreach (eqLogic::byType('mobile') as $mobile){
			if($mobile->getLogicalId() == null || $mobile->getLogicalId() == ""){
				$mobile->remove();
			}
		}
}
