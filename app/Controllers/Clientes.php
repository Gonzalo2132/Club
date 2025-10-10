<?php

namespace App\Controllers;

use App\Models\ClientesModel; //Acá asignamos que usamos el modelo designado



class Clientes extends BaseController
{
    protected $clientes; //Esto es para que la variable en cuestion este definida

    public function __construct() { //La función constructora
        $this->clientes = new ClientesModel();
    }

    public function index($estatus = 1)
    {
        //Esto seria una consulta,  "Select * from clientes"
        $clientes = $this->clientes->where('estatus', $estatus)->findAll();
        //Construyo el context
        //['llave' => valor, 'llave2'=>valor2, etc] llave = nombre variable

        $context = ['clientes'=>$clientes,
                        'titulo'=>"gestion de clientes",
                        'pagname'=>"Gestion/clientes"];


        
        echo view('panel/header', $context);
        echo view('clientes/listado', $context);
        echo view('panel/footer');
 
    }
//esta funcion "nuevo "muestra el header el footer y la vista que va a tener el formulario
    public function nuevo(){
        //este context es para cambiar el titulo de la pagina que esta esperando el header
        $context = ['titulo' => "nuevo cliente",
                    'pagname' => 'Gestión/Nuevo cliente'];

        echo view ('panel/header',$context);
        echo view ('clientes/nuevo');
        echo view ('panel/footer');
    }
    public function guardar(){
        $this->clientes->save(
            ['denominacion'=>$this->request->getPost('denominacion'),
            'domicilio'=>$this->request->getPost('domicilio'),
            'email'=>$this->request->getPost('email'),
            
            ]
        );
        return redirect()->to(base_url().'public/clientes/');

        }
        public function borrar($id){
            $this->clientes->update($id,['estatus'=>0]);
            return redirect()->to(base_url().'public/clientes/');

        }
        public function editar($id){
            $cliente = $this->clientes->where('id', $id)->findAll();
            $context = ['cliente'=>$cliente,
                    'titulo' => "edicion cliente",
                    'pagname' => 'Gestión/Edición cliente'];


        
            echo view ('panel/header',$context);
            echo view ('clientes/editar');
            echo view ('panel/footer');
        }
        public function actualizar($id){
            //primer parametro es el where en este caso $id
            $this->clientes->update($id,
            ['denominacion'=>$this->request->getPost('denominacion'),
            'domicilio'=>$this->request->getPost('domicilio'),
            'email'=>$this->request->getPost('email'),
            ]
        );
                    return redirect()->to(base_url().'public/clientes/');


        }

       
    }
    
