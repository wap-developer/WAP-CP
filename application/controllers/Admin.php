<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('email')) {
            $this->session->set_flashdata('notification', '<div class="kt-alert kt-alert--outline alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><span>Maaf! Silakan masuk terlebih dahulu.</span></div>');
            redirect('cp-admin/auth/login');
        }

        $this->load->model('admin_m', 'admin');
    }

    public function dashboard()
    {
        $data = array(
            'role' => $this->admin->getCurrentRole(),
            'user' => $this->admin->getActiveUser(),
            'check' => $this->admin->getSeo(),
            'title' => 'Dashboard'
        );

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('template/navbar', $data);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('template/footer');
    }

    public function seo()
    {
        $this->form_validation->set_rules('title', 'Title Website', 'required|max_length[60]', array(
            'required' => '%s Harus diisi.',
            'max_length' => '%s Tidak boleh lebih dari 60 karakter.'
        ));
        $this->form_validation->set_rules('description', 'Description', 'max_length[160]', array(
            'max_length' => '%s Tidak boleh lebih dari 160 karakter.'
        ));
        $this->form_validation->set_rules('crawl_landing', 'Crawl Landing Page', 'required', array(
            'required' => '%s Harus dipilih',
        ));
        $this->form_validation->set_rules('follow_landing', 'Follow Landing Page', 'required', array(
            'required' => '%s Harus dipilih',
        ));
        $this->form_validation->set_rules('crawl_admin', 'Crawl Admin Panel', 'required', array(
            'required' => '%s Harus dipilih',
        ));
        $this->form_validation->set_rules('follow_admin', 'Follow Admin Panel', 'required', array(
            'required' => '%s Harus dipilih',
        ));

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'user' => $this->admin->getActiveUser(),
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash(),
                'check' => $this->admin->getSeo(),
                'title' => "SEO Management"
            );
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('template/navbar', $data);
            $this->load->view('admin/seo', $data);
            $this->load->view('template/footer');
        } else {
            $title = $this->input->post('title');
            $description = $this->input->post('description');
            $meta = $this->input->post('meta');
            $cl = $this->input->post('crawl_landing');
            $fl = $this->input->post('follow_landing');
            $ca = $this->input->post('crawl_admin');
            $fa = $this->input->post('follow_admin');
            $check = $this->admin->getSeo();

            $data = [
                'title' => $title,
                'description' => $description,
                'meta' => $meta,
                'crawl_landing' => $cl,
                'follow_landing' => $fl,
                'crawl_admin' => $ca,
                'follow_admin' => $fa,
                'update_at' => date('Y-m-d', time())
            ];

            if (!$check) {
                $this->admin->insertSeo($data);
            } else {
                $this->admin->updateSeo($title, $data);
            }

            $this->session->set_flashdata('notification', '<div class="kt-alert kt-alert--outline alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><span>Selamat! SEO Website Telah di Simpan.</span></div>');
            redirect('cp-admin/seo-management');
        }
    }

    public function profile()
    {
        $password = $this->input->post('password');
        $confirm = $this->input->post('confirm');
        $this->form_validation->set_rules('name', 'Nama', 'required', array(
            'required' => '%s Harus diisi.'
        ));
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email', array(
            'required' => '%s Harus diisi.',
            'valid_email' => 'Penulisan %s yang anda masukan salah'
        ));

        if ($password != NULL || $confirm != NULL) {
            $this->form_validation->set_rules('password', 'kata Sandi', 'required|min_length[6]|matches[confirm]', array(
                'required' => '%s Harus diisi.',
                'min_length' => '%s Minimal 6 karakter.',
                'matches' => '%s Yang anda masukan tidak sama.'
            ));
            $this->form_validation->set_rules('confirm', 'kata Sandi', 'required|min_length[6]|matches[password]', array(
                'required' => '%s Harus diisi.',
                'min_length' => '%s Minimal 6 karakter.'
            ));
        }

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash(),
                'user' => $this->admin->getActiveUser(),
                'check' => $this->admin->getSeo(),
                'title' => 'Profile Pengguna'
            );
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('template/navbar', $data);
            $this->load->view('admin/profile', $data);
            $this->load->view('template/footer');
        } else {
            $id = $this->session->userdata('id');
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $oldpassword = $this->admin->getOldPassword();

            if ($password != NULL || $confirm != NULL) {
                $pwd = md5($password);
            } else {
                $pwd = $oldpassword['password'];
            }

            $data = [
                'name' => $name,
                'email' => $email,
                'password' => $pwd
            ];

            $this->admin->updateUser($id, $data);

            $this->session->set_flashdata('notification', '<div class="kt-alert kt-alert--outline alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><span>Selamat! Profil anda berhasil diupdate.</span></div>');
            redirect('cp-admin/profile');
        }
    }

    public function gallery()
    {
        $data = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash(),
            'user' => $this->admin->getActiveUser(),
            'check' => $this->admin->getSeo(),
            'title' => 'Album Kegiatan'
        );
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('template/navbar', $data);
        $this->load->view('admin/album', $data);
        $this->load->view('template/footer');
    }

    public function role()
    {
        $this->form_validation->set_rules('role', 'Role', 'required', array(
            'required' => '%s Harus diisi.'
        ));

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash(),
                'user' => $this->admin->getActiveUser(),
                'check' => $this->admin->getSeo(),
                'roles' => $this->admin->getRoles(),
                'title' => 'Role Management'
            );
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('template/navbar', $data);
            $this->load->view('admin/role', $data);
            $this->load->view('template/footer');
        } else {
            if ($this->input->post('add')) {
                $roleInput = $this->input->post('role');
                $data = [
                    'role' => $roleInput,
                    'create_at' => date('Y-m-d', time())
                ];

                $this->admin->insertRole($data);

                $this->session->set_flashdata('notification', '<div class="kt-alert kt-alert--outline alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><span>Selamat! Role berhasil ditambahkan.</span></div>');
                redirect('cp-admin/role-management');
            }

            if ($this->input->post('edit')) {
                $id = $this->input->post('id');
                $roleInput = $this->input->post('role');
                $data = [
                    'role' => $roleInput,
                    'create_at' => date('Y-m-d', time())
                ];

                $this->admin->updateRole($id, $data);

                $this->session->set_flashdata('notification', '<div class="kt-alert kt-alert--outline alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><span>Selamat! Role berhasil ditambahkan.</span></div>');
                redirect('cp-admin/role-management');
            }
        }
    }

    public function delete_role($id)
    {
        $this->admin->deleteRole($id);
        $this->session->set_flashdata('notification', '<div class="kt-alert kt-alert--outline alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><span>Selamat! Role berhasil ditambahkan.</span></div>');
        redirect('cp-admin/role-management');
    }

    public function menu()
    {
        if ($this->input->post('addMenu')) {
            $menu = $this->input->post('menu');
            $icon = $this->input->post('icon');
            $url = $this->input->post('url');

            $data = [
                'menu' => $menu,
                'icon' => $icon,
                'url' => $url,
                'update_at' => date('Y-m-d', time())
            ];

            $this->admin->insertMenu($data);
            $this->session->set_flashdata('notificationa', '<div class="kt-alert kt-alert--outline alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><span>Selamat! Menu berhasil ditambahkan.</span></div>');
            redirect('cp-admin/menu-management/');
        } else if ($this->input->post('addSub')) {
            $menu = $this->input->post('menu');
            $sub = $this->input->post('sub');
            $url = $this->input->post('url');

            $data = [
                'menu_id' => $menu,
                'sub_menu' => $sub,
                'sub_url' => $url,
                'update_at' => date('Y-m-d', time())
            ];

            $this->admin->insertSubMenu($data);
            $this->session->set_flashdata('notificationb', '<div class="kt-alert kt-alert--outline alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><span>Selamat! Sub-Menu berhasil ditambahkan.</span></div>');
            redirect('cp-admin/menu-management/');
        } else if ($this->input->post('editMenu')) {
            $id = $this->input->post('id');
            $menu = $this->input->post('menu');
            $icon = $this->input->post('icon');
            $url = $this->input->post('url');

            $data = [
                'menu' => $menu,
                'icon' => $icon,
                'url' => $url,
                'update_at' => date('Y-m-d', time())
            ];

            $this->admin->updateMenu($id, $data);
            $this->session->set_flashdata('notificationa', '<div class="kt-alert kt-alert--outline alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><span>Selamat! Menu berhasil diubah.</span></div>');
            redirect('cp-admin/menu-management/');
        }

        $data = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash(),
            'getMenus' => $this->admin->getMenu(),
            'getSubMenus' => $this->admin->getSubMenu(),
            'user' => $this->admin->getActiveUser(),
            'check' => $this->admin->getSeo(),
            'roles' => $this->admin->getRoles(),
            'title' => 'Menu Manajemen'
        );
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('template/navbar', $data);
        $this->load->view('admin/menu', $data);
        $this->load->view('template/footer');
    }

    public function delete_menu($id)
    {
        $this->admin->deleteMenu($id);
        $this->session->set_flashdata('notificationa', '<div class="kt-alert kt-alert--outline alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><span>Selamat! Menu berhasil dihapus.</span></div>');
        redirect('cp-admin/menu-management/');
    }

    public function menu_role($id)
    {
        $data = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash(),
            'user' => $this->admin->getActiveUser(),
            'check' => $this->admin->getSeo(),
            'roles' => $this->admin->getRoles(),
            'title' => 'Role Menu'
        );
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('template/navbar', $data);
        $this->load->view('admin/role_menu', $data);
        $this->load->view('template/footer');
    }
}
