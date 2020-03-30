<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EnrollmentForm extends ASPA_Controller 
{

	function __construct() {
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('EnrollmentForm');
	}

	/**
	 * validate() is called in assets/js/enrollmentForm.js via ajax POST method.
	 * The functionality is to determine if the inputted email is of the correct:
	 *  - email format
	 *  - is an email on the email spreadsheet
	 */
	public function validate() {
		$emailAddress = $this->input->post('emailAddress');
		$this->load->model('Verification_Model', 'verificationModel');
		if ($this->verificationModel->has_user_paid($emailAddress)) {
			$this->create_json('True', '', 'Success');
		} else {
			if ($this->verificationModel->is_email_on_sheet($emailAddress)){
				$this->create_json('False', '', 'Error: signed up but not paid');
			} else {
				$this->create_json('False', '', 'Error: not signed up');
			}
		}
	}

}

/* End of file EnrollmentForm.php */
/* Location: ./application/controllers/EnrollmentForm.php */