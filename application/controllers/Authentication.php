<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Authentication extends CI_Controller
{
	public function index()
	{
		if (!$this->session->userdata('email')) {
			redirect('cp-admin/auth/login');
		} else {
			redirect('cp-admin/dashboard');
		}
	}

	public function login()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$this->form_validation->set_rules('email', 'Email', 'required|valid_email', array(
			'required' => '%s Harus diisi.',
			'valid_email' => 'Penulisan %s salah.'
		));
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]', array(
			'required' => '%s Harus diisi',
			'min_length' => '%s Password kurang dari 6 karakter'
		));

		if ($this->form_validation->run() == FALSE) {
			$data = array(
				'name' => $this->security->get_csrf_token_name(),
				'hash' => $this->security->get_csrf_hash(),
				'check' => $this->db->get('wb_seo')->row_array(),
				'title' => "Admin Login"
			);
			$this->load->view('auth/header', $data);
			$this->load->view('auth/index', $data);
			$this->load->view('auth/footer');
		} else {
			$this->_login_process($email, $password);
		}
	}

	private function _login_process($email, $password)
	{

		$check = $this->db->get_where('wb_admin', array('email' => $email))->row_array();

		if ($check) {
			if ($check['password'] == md5($password)) {
				$session = array(
					'id' => $check['id'],
					'name'  => $check['name'],
					'email'     => $check['email'],
					'role'     => $check['role_id'],
					'logged_in' => TRUE
				);
				$this->session->set_userdata($session);
				redirect('cp-admin/dashboard');
			} else {
				$this->session->set_flashdata('notification', '<div class="kt-alert kt-alert--outline alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><span>Maaf! Password anda salah.</span></div>');
				redirect('cp-admin/auth/login');
			}
		} else {
			$this->session->set_flashdata('notification', '<div class="kt-alert kt-alert--outline alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><span>Maaf! Email tidak ditemukan.</span></div>');
			redirect('cp-admin/auth/login');
		}
	}

	public function logout_process()
	{
		$this->session->sess_destroy();
		redirect('authentication/logout');
	}

	public function logout()
	{
		$this->session->set_flashdata('notification', '<div class="kt-alert kt-alert--outline alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><span>Terimakasih telah menggunakan layanan ini.</span></div>');
		redirect('cp-admin/auth/login');
	}
}
