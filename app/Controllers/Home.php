<?php namespace App\Controllers;

Use App\Models\PersonasModel;
Use App\Models\InstitutosModel;
Use App\Models\Gestion_AlumnosModel;

class home extends BaseController
{
	protected $session, $personas, $alumnos, $institutos;
	
	public function __construct()
	{
		$this->session = session();
		$this->personas = new PersonasModel();
		$this->institutos = new InstitutosModel();
		$this->alumnos = new Gestion_AlumnosModel();
	}

	public function index()
	{
		if(!isset($this->session->user_id)) {return redirect()->to(base_url());}
		
		$persona=$this->personas->where('user_email', $this->session->user_email)->first();
        $data=['vDATOS' => $persona];

		echo view('header');
		echo view('home',$data);
		echo view('footer');
	}

    // Ayuda
	public function ayuda()
	{
		if(!isset($this->session->user_id)) {return redirect()->to(base_url());}

        echo view('header');
        echo view('ayuda');
        echo view('footer');
	}

}
