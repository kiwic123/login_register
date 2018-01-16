<?php 
class PostVeridator {

    public function isValidEmail( $email )
    {
        $data_array['email'] = $email;
        $gump = new GUMP();
        $data_array = $gump->sanitize($data_array); 
        $validation_rules_array = array(
            'email'    => 'required|valid_email'
        );
        $gump->validation_rules($validation_rules_array);
        $filter_rules_array = array(
            'email' => 'trim|sanitize_email'
        );
        $gump->filter_rules($filter_rules_array);
        $validated_data = $gump->run($data_array);
        if($validated_data === false) {
            $error = $gump->get_readable_errors(false);
            $msg = new \Plasticbrain\FlashMessages\FlashMessages();
            foreach( $error as $e) {
                $msg->error($e);
            }
            return false;
        } else {
            return true;
        }
    }
    
}