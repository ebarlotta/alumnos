<?php namespace App\Controllers;

Use App\Models\PersonasModel;
Use App\Models\InstitutosModel;
Use App\Models\CarrerasModel;
Use App\Models\MateriasModel;
use App\Models\SettingsModel;

Use App\Models\Gestion_InscripcionesModel;
Use App\Models\Gestion_AlumnosModel;
Use App\Models\Gestion_CarrerasModel;
Use App\Models\Gestion_MesasModel;
Use App\Models\Gestion_ExamenesModel;
use App\Models\Gestion_MateriasModel;
use App\Models\Gestion_ProfesoresModel;

class alumnos extends BaseController
{
	protected $session, $settings,
    $personas, $institutos, $carreras, 
    $gestion_inscripciones, $gestion_materias, $gestion_alumnos, $gestion_carreras, $gestion_examenes, $gestion_mesas;
    protected $materia, $gestion_profesores;
	
	public function __construct()
	{
		$this->session = session();
        $this->settings = new SettingsModel();

		$this->personas = new PersonasModel();
		$this->institutos = new InstitutosModel();
        $this->carreras = new CarrerasModel();

        $this->gestion_inscripciones = new Gestion_InscripcionesModel();
        $this->gestion_materias = new Gestion_MateriasModel();
        $this->gestion_alumnos = new Gestion_AlumnosModel();
		$this->gestion_carreras = new Gestion_CarrerasModel();
        $this->gestion_mesas = new Gestion_MesasModel();
        $this->gestion_examenes = new Gestion_ExamenesModel();
        $this->materia = new MateriasModel();
        $this->gestion_profesores = new Gestion_ProfesoresModel();
	}

    // Inscripciones a Carreras
	public function index()
	{
		if(!isset($this->session->user_id)) {return redirect()->to(base_url());}

        // Inscripciones Aceptadas
		$inscripciones=$this->gestion_alumnos
        ->select('gestion_alumnos.*, institutos.numero, carreras.nombre, carreras.resolucion')
		->where('id_persona', $this->session->user_id)
		->join('institutos', 'institutos.id = gestion_alumnos.id_instituto')
		->join('carreras', 'carreras.id = gestion_alumnos.id_carrera')
		->findAll();
        // Inscripciones Pendientes
        $pendientes=$this->gestion_inscripciones
        ->select('gestion_inscripciones.*, institutos.numero, carreras.nombre, carreras.resolucion')
		->where('id_persona', $this->session->user_id)
		->join('institutos', 'institutos.id = gestion_inscripciones.id_instituto')
		->join('carreras', 'carreras.id = gestion_inscripciones.id_carrera')
		->findAll();

		$data =['vTITULO' => 'Inscripciones a Carreras', 'vDATOS' => $inscripciones, 'vPENDIENTES' => $pendientes];

		echo view('header');
		echo view('alumnos/inscripciones', $data);
		echo view('footer');	
	}

    // Nueva Inscripcion a Carrera
    public function nueva_inscripcion()
    {
        if(!isset($this->session->user_id)) {return redirect()->to(base_url());}
        $abierta=$this->settings->where('id',1)->first();

        if ($abierta['inscripciones'] == 'SI'){

            // Institutos y Carreras Asignadas
            $carreras=$this->gestion_carreras
            ->select('gestion_carreras.*, institutos.numero, carreras.nombre, carreras.resolucion')
            ->where('estado','ACTIVO')
            ->where('nueva_cohorte','SI')
            ->join('institutos', 'institutos.id = gestion_carreras.id_instituto')
            ->join('carreras', 'carreras.id = gestion_carreras.id_carrera')
            ->orderBy('institutos.numero','asc')
            ->findAll();

            $data=['vTITULO' => 'Nueva Inscripción a Carrera', 'vCARRERAS' => $carreras];

            echo view('header');
            echo view('alumnos/nueva_inscripcion',$data);
            echo view('footer');

        } else {
            echo view('header');
            echo view('acceso');
            echo view('footer');
        }
    }

    // Guardar Nueva Inscripcion
    public function inscribir()
    {
        if(!isset($this->session->user_id)) {return redirect()->to(base_url());}

        $vCARRERA=$this->gestion_carreras->where('id',$this->request->getPost('carreras'))->first();

        if (!empty($vCARRERA) && $vCARRERA['nueva_cohorte'] == 'SI'){

            // Revisamos si ya existe una inscripcion a la carrera este año
            $existe=$this->gestion_inscripciones
            ->where('id_persona',$this->session->user_id)
            ->where('id_instituto',$vCARRERA['id_instituto'])
            ->where('id_carrera',$vCARRERA['id_carrera'])
            ->where('anolectivo',2025)
            ->first();

            if (empty($existe)) {
                $this->gestion_inscripciones->save([
                    'id_persona' => $this->session->user_id,
                    'id_instituto' => $vCARRERA['id_instituto'],
                    'id_carrera' => $vCARRERA['id_carrera'],
                    'anolectivo' => 2025,
                ]);
            }

        }

        return redirect()->to(base_url().'/alumnos/index');
    }

    // Eliminar Inscripcion Pendiente
    public function eliminar_pendiente($vID)
    {
        if(!isset($this->session->user_id)) {return redirect()->to(base_url());}
        $id=openssl_decrypt(base64_decode($vID),'AES-128-ECB',$this->session->user_id);

        $verifica=$this->gestion_inscripciones->where('id',$id)->first();

        if(!empty($verifica)) {
            // Borramos Inscripcion pendiente
            $this->gestion_inscripciones->delete($id);        

            return redirect()->to(base_url().'/alumnos/index');
        } else {
            echo view('header');
            echo view('error');
            echo view('footer');
        }
    } 

    // Listado General de Examenes
    public function examenes()
    {
        if(!isset($this->session->user_id)) {return redirect()->to(base_url());}
        
        $datos=$this->gestion_examenes->select('gestion_examenes.*, gestion_mesas.*, gestion_materias.nombre, carreras.nombre AS vCARRERA, institutos.numero')
        ->where('id_persona',$this->session->user_id)
        ->join('gestion_mesas', 'gestion_mesas.id = gestion_examenes.id_mesa')
        ->join('gestion_materias', 'gestion_materias.id = gestion_mesas.id_materia')
        ->join('carreras', 'carreras.id = gestion_mesas.id_carrera')
        ->join('institutos', 'institutos.id = gestion_mesas.id_instituto')
        ->findAll();
        $data=['vTITULO' => 'Historial General de Exámenes', 'datos' => $datos];

        echo view('header');
        echo view('alumnos/examenes',$data);
        echo view('footer');
    }

    // Listado General de Examenes de carrera
    public function examenes_carrera($id)
    {
        if(!isset($this->session->user_id)) {return redirect()->to(base_url());}
        $carrera=$this->carreras->where('id',$id)->first();

        $datos=$this->gestion_examenes->select('gestion_examenes.*, gestion_mesas.*, gestion_materias.nombre, institutos.numero')
        ->where('id_persona',$this->session->user_id)
        ->where('gestion_mesas.id_carrera',$id)
        ->join('gestion_mesas', 'gestion_mesas.id = gestion_examenes.id_mesa')
        ->join('gestion_materias', 'gestion_materias.id = gestion_mesas.id_materia')
        ->join('institutos', 'institutos.id = gestion_mesas.id_instituto')
        ->findAll();
        $data=['vTITULO' => 'Exámenes de Carrera', 'datos' => $datos, 'vCARRERA' => $carrera];

        echo view('header');
        echo view('alumnos/examenes_carrera',$data);
        echo view('footer');
    }

    // Listado de Materias de carrera
    public function materias_carrera($id)
    {
        if(!isset($this->session->user_id)) {return redirect()->to(base_url());}
        $carrera=$this->carreras->where('id',$id)->first();

        $datos=$this->gestion_materias->select('gestion_materias.*')
        //->where('id_persona',$this->session->user_id)
        ->where('gestion_materias.carrera',$id)
        //->join('gestion_mesas', 'gestion_mesas.id = gestion_examenes.id_mesa')
        //->join('materias', 'materias.id = gestion_mesas.id_materia')
        ->findAll();
        $data=['vTITULO' => 'Materias de la Carrera', 'vDATOS' => $datos, 'vCARRERA' => $carrera];

        echo view('header');
        echo view('alumnos/materias_carrera',$data);
        echo view('footer');
    }

    public function informacion_materias($id)
    {
        if(!isset($this->session->user_id)) {return redirect()->to(base_url());}
        
        $materia=$this->materia->where('id',$id)->first();

        // Arreglar 
        $registro=$this->gestion_materias->select('carreras.nombre, materias.nombre as nombre_materia')
        ->where('gestion_materias.id',$id)
        ->join('carreras', 'carreras.id = gestion_materias.carrera')
        ->join('materias', 'materias.id = gestion_materias.id')
        ->first();
        // dd($registro);
        
        // $carrera=$this->carreras->where('id',$id)->first();
        
        $datos=$this->gestion_profesores->select('gestion_profesores.*, personas.user_apellido, personas.user_nombres, institutos.numero, institutos.nombre as nombre_instituto, materias.ano, materias.regimen, materias.cuatrimestre, materias.formato')
        ->where('gestion_profesores.id_materia',$id)
        ->join('personas', 'personas.id = gestion_profesores.id_persona')
        ->join('institutos', 'institutos.id = gestion_profesores.id_instituto')
        ->join('materias','materias.id = gestion_profesores.id_materia')
        ->findAll();
        
        // dd($datos);

        $data=['vTITULO' =>$registro['nombre_materia'], 'vDATOS' => $datos, 'vCARRERA' =>$registro['nombre']];

        echo view('header');
        echo view('alumnos/informacion_materias',$data);
        echo view('footer');
    }

    public function listado() {
        // dd('entro');
        $html = '<select name="" id="" class="form-control" hx-get="'. base_url() . '/alumnos/listado2" hx-target="#listadoNuevo" hx-trigger="change">
                        <option value="">Opcion 11</option>
                        <option value="">Opcion 21</option>
                        <option value="">Opcion 31</option>
                    </select>';

        return $html;
    }

    public function listado2() {
        // dd('entro');
        $html = '<select name="" id="" class="form-control" hx-get="'. base_url() . '>/alumnos/listado3" hx-target="#listadoNuevo" hx-trigger="change">
                        <option value="">Opcion 11</option>
                        <option value="">Opcion 21</option>
                        <option value="">Opcion 31</option>
                    </select>';

        return '<script>alert("prueba");</script>';
        return $html;
    }
}
