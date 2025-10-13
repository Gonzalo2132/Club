<?php

namespace App\Controllers;

use App\Models\UsuariosModel; 

class Usuarios extends BaseController
{
    protected $usuarios; 
    public function __construct() { 
        $this->usuarios = new UsuariosModel();
    }

    public function index($activo = 1)
    {
        $usuarios = $this->usuarios->where('activo', $activo)->findAll();
        
        $context = ['usuarios'=>$usuarios,
                        'titulo'=>"Usuarios",
                        'pagname'=>"Gestión/Usuarios"];
        
        echo view('panel/header', $context);
        echo view('usuarios/listado', $context);
        echo view('panel/footer');
 
    }
    public function nuevo(){
        $context = ['titulo' => "Nuevo usuario",
                    'pagname' => 'Gestión/Nueva usuario'];

        echo view ('panel/header',$context);
        echo view ('usuarios/nuevo');
        echo view ('panel/footer');
    }
    public function guardar(){
        $this->usuarios->save(
            ['nombre'=>$this->request->getPost('nombre'),
            'apellido'=>$this->request->getPost('apellido'),
            'username'=>$this->request->getPost('username'),
            'clave'=>$this->request->getPost('clave'),
            'dni'=>$this->request->getPost('dni')
            ]
        );
        return redirect()->to(base_url().'public/usuarios/');
        }
        public function borrar($id){
            $this->usuarios->update($id,['activo'=>0]);
            return redirect()->to(base_url().'public/usuarios/');
        }
        public function editar($id){
            $usuario = $this->usuarios->where('id', $id)->findAll();
            $context = ['usuario'=>$usuario,
                    'titulo' => "Edición usuario",
                    'pagname' => 'Gestión/Edición usuario'];

        
            echo view ('panel/header',$context);
            echo view ('usuarios/editar');
            echo view ('panel/footer');
        }
        public function actualizar($id){
            $this->usuarios->update($id,
            ['nombre'=>$this->request->getPost('nombre'),
            'apellido'=>$this->request->getPost('apellido'),
            'username'=>$this->request->getPost('username'),
            'clave'=>$this->request->getPost('clave'),
            'dni'=>$this->request->getPost('dni')
            
            ]
        );
                    return redirect()->to(base_url().'public/usuarios/');

        }
      
    }
    
