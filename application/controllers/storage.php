<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Storage extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
    }
	

	public function other_search() {
		$this->load->view('storage/other-search');	
	}
	

	public function inventory() {
	    $this->common_model->checkpurview(11);
        $where  = '(isDelete=0) and typeNumber="brand"';
        $data['list'] = $this->mysql_model->get_results('category',$where,'path');
	    $this->load->view('storage/inventory',$data);
	}
	

	public function transfers_search () {
	    $this->load->view('storage/transfers-search');	
	}
	

	public function import () {
	    $this->load->view('storage/import');	
	}
	
	
	 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */