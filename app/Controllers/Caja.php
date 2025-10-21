<?php

namespace App\Controllers;

use App\Models\CajasModel; //Acá asignamos que usamos el modelo designado



class Cajas extends BaseController
{
    protected $cajas; //Esto es para que la variable en cuestion este definida

    public function __construct() { //La función constructora
        $this->cajas = new CaajsModel();
    }

    public function index($activo = 1)
    {
        //Esto seria una consulta,  "Select * from clientes"
        $cajas = $this->cajas->where('activo', $activo)->findAll();
        //Construyo el context
        //['llave' => valor, 'llave2'=>valor2, etc] llave = nombre variable

        $context = ['cajas'=>$zonas,
                        'titulo'=>"Caja",
                        'pagname'=>"Gestión/Cajas"];


        
        echo view('panel/header', $context);
        echo view('cajas/listado', $context);
        echo view('panel/footer');
 
    }
//esta funcion "nuevo "muestra el header el footer y la vista que va a tener el formulario
    public function nuevo(){
        //este context es para cambiar el titulo de la pagina que esta esperando el header
        $context = ['titulo' => "Nueva caja",
                    'pagname' => 'Gestión/Nueva caja'];

        echo view ('panel/header',$context);
        echo view ('cajas/nuevo');
        echo view ('panel/footer');
    }
    public function guardar(){
        $this->cajas->save(
            ['denominacion'=>$this->request->getPost('denominacion'),
            'codigointerno'=>$this->request->getPost('codigointerno')
        
            ]
        );
        return redirect()->to(base_url().'public/cajas/');

        }
        public function borrar($id){
            $this->zonas->update($id,['activo'=>0]);
            return redirect()->to(base_url().'public/cajas/');

        }
        public function editar($id){
            $caja = $this->cajas->where('id', $id)->findAll();
            $context = ['caja'=>$caja,
                    'titulo' => "Edición caja",
                    'pagname' => 'Gestión/Edición caja'];


        
            echo view ('panel/header',$context);
            echo view ('cajas/editar');
            echo view ('panel/footer');
        }
        public function actualizar($id){
            //primer parametro es el where en este caso $id
            $this->cajas->update($id,
            ['denominacion'=>$this->request->getPost('denominacion'),
            'codigointerno'=>$this->request->getPost('codigointerno')
        
            ]
        );
                    return redirect()->to(base_url().'public/cajas/');


        }

       
    }
    
