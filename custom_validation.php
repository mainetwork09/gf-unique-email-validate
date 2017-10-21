
add_filter( 'gform_validation_2', 'custom_validation' );

function custom_validation( $validation_result ){
	global $wpdb; 

    $form = $validation_result['form'];
	
	//supposing we don't want input 1 to be a value of 86
	if ( rgpost( 'input_1' ) ) {
	
		// set the form validation to false
		$validation_result['is_valid'] = false;
	
		//finding Field with ID of 1 and marking it as failed validation
		foreach( $form['fields'] as &$field ) {
	
			//NOTE: replace 1 with the field you would like to validate
			if ( $field->id == '1' ) {
				$email = rgpost( 'input_1' );
				$sql = "SELECT count(*) FROM " .$wpdb->prefix. "rg_lead_detail WHERE form_id = '".$form['id']."' AND field_number = '".$field->id."' AND value='" .$email. "'";
				//echo $sql;
				$get_count = $wpdb->get_var( $sql );
				if( $get_count > 0 ){
					$field->failed_validation = true;
					$field->validation_message = __('You have already subscribed!','gravity-form');
					break;
				}else{
					$validation_result['is_valid'] = true;
				}
			}
		}
	}
	
	//Assign modified $form object back to the validation result
	$validation_result['form'] = $form;
	return $validation_result;
}