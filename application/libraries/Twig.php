<?php
class Twig {
	private $CI;

	private $_config = array();
	private $_twig;
	private $_twig_loader;

	public function __construct(){
		log_message('debug', 'Twig: library initialized');

		$this->CI =& get_instance();

		$this->_config = $this->CI->config->item('twig');

		try {
			$this->_twig_loader = new Twig_Loader_Filesystem($this->_config['template_dir']);
		} catch (Exception $e) {
			show_error(htmlspecialchars_decode($e->getMessage()), 500, 'Twig Exception');
		}

		if($this->_config['environment']['cache'] === true){
			$this->_config['environment']['cache'] = APPPATH.'cache/twig';
		}

		$this->_twig = new Twig_Environment($this->_twig_loader, $this->_config['environment']);

		//debug sonrası bu satır kaldırılacak
		$this->_twig->addExtension(new \Twig\Extension\DebugExtension());

		$this->ciFunctionList();
	}

	public function ciFunctionList()
	{
		$this->addFunction('base_url', 'base_url');
		$this->addFunction('site_url', 'site_url');
        $this->addFunction("set_value", "set_value");

		//get_defined_functions ile ön tanımlı fonksiyon listesi döner
		 foreach(get_defined_functions() as $functions) {
	 	 	foreach($functions as $function) {
                $this->addFunction($function, $function);
		 	}
	    }
		//System/helpers/form_helper.php içidne bu metodlar tanımlı olduğu için bu şekilde kullanabiliyoruz
		// $this->addFunction('current_url', 'current_url');
		// $this->addFunction('current_path', 'current_path');
		// $this->addFunction('form_open', 'form_open');
		// $this->addFunction('form_hidden', 'form_hidden');
		// $this->addFunction('form_input', 'form_input');
		// $this->addFunction('form_password', 'form_password');
		// $this->addFunction('form_upload', 'form_upload');
		// $this->addFunction('form_textarea', 'form_textarea');
		// $this->addFunction('form_dropdown', 'form_dropdown');
		// $this->addFunction('form_multiselect', 'form_multiselect');
		// $this->addFunction('form_fieldset', 'form_fieldset');
		// $this->addFunction('form_fieldset_close', 'form_fieldset_close');
		// $this->addFunction('form_checkbox', 'form_checkbox');
		// $this->addFunction('form_radio', 'form_radio');
		// $this->addFunction('form_submit', 'form_submit');
		// $this->addFunction('form_label', 'form_label');
		// $this->addFunction('form_reset', 'form_reset');
		// $this->addFunction('form_button', 'form_button');
		// $this->addFunction('form_close', 'form_close');
		// $this->addFunction('form_prep', 'form_prep');
		// $this->addFunction('set_value', 'set_value');
		// $this->addFunction('set_select', 'set_select');
		// $this->addFunction('set_checkbox', 'set_checkbox');
		// $this->addFunction('set_radio', 'set_radio');
		// $this->addFunction('form_open_multipart', 'form_open_multipart');

	}

	public function addFunction($twig_name, $callable)
	{
	   $this->_twig->addFunction(
	     new Twig_SimpleFunction($twig_name, $callable, array('is_safe' => array('html')))
	   );
	}

	public function render($template, $data = array()){
		$template = $this->addExtension($template);
		return $this->_twig->render($template, $data);
	}

	public function display($template, $data = array()){
		$this->_twig->display($template, $data);
	}

	private function addExtension($template){
		$ext = '.'.$this->_config['template_ext'];

		if(substr($template, -strlen($ext)) !== $ext){
			return $template .= $ext;
		}

		return $template;
	}
}
