<?php

namespace App\Controllers;

use App\Models\SociosModel; //Acá asignamos que usamos el modelo designado



class Socios extends BaseController
{
    protected $socios; //Esto es para que la variable en cuestion este definida

    public function __construct() { //La función constructora
        $this->socios = new SociosModel();
    }

    public function index($estatus = 1)
    {
        //Esto seria una consulta,  "Select * from socios"
        $socios = $this->socios->where('estatus', $estatus)->findAll();
        //Construyo el context
        //['llave' => valor, 'llave2'=>valor2, etc] llave = nombre variable

        $context = ['socios'=>$socios,
                        'titulo'=>"gestion de socios",
                        'pagname'=>"Gestion/clientes"];


        
        echo view('panel/header', $context);
        echo view('clientes/listado', $context);
        echo view('panel/footer');
 
    }
//esta funcion "nuevo "muestra el header el footer y la vista que va a tener el formulario
    public function nuevo(){
        //este context es para cambiar el titulo de la pagina que esta esperando el header
        $context = ['titulo' => "nuevo Socio",
                    'pagname' => 'Gestión/Nuevo Socio'];

        echo view ('panel/header',$context);
        echo view ('socios/nuevo');
        echo view ('panel/footer');
    }
    public function guardar(){
        $this->socios->save(
            ['denominacion'=>$this->request->getPost('denominacion'),
            'domicilio'=>$this->request->getPost('domicilio'),
            'email'=>$this->request->getPost('email'),
            
            ]
        );
        return redirect()->to(base_url().'public/socios/');

        }
        public function borrar($id){
            $this->socios->update($id,['estatus'=>0]);
            return redirect()->to(base_url().'public/socios/');

        }
        public function editar($id){
            $socio = $this->socios->where('id', $id)->findAll();
            $context = ['socio'=>$socio,
                    'titulo' => "edicion socio",
                    'pagname' => 'Gestión/Edición socio'];


        
            echo view ('panel/header',$context);
            echo view ('clientes/editar');
            echo view ('panel/footer');
        }
        public function actualizar($id){
            //primer parametro es el where en este caso $id
            $this->socios->update($id,
            ['denominacion'=>$this->request->getPost('denominacion'),
            'domicilio'=>$this->request->getPost('domicilio'),
            'email'=>$this->request->getPost('email'),
            ]
        );
                    return redirect()->to(base_url().'public/clientes/');


        }

       
    }
    
