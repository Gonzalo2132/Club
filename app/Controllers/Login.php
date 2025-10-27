<?php

namespace App\Controllers;

use App\Models\UsuariosModel; //Acá asignamos que usamos el modelo designado

class Login extends BaseController
{
    protected $usuarios;
    public function __construct()
    {
        $this->usuarios = new UsuariosModel(); //Instanciamos el modelo
    }

    public function index()
    {
        echo view('login/login');
    }

    public function validar(){
        $usuario = $this->request->getPost('username');
        $password = $this->request->getPost('clave');
        $datosusuario = $this->usuarios->where('username', $usuario)->first();

        if ($datosusuario != null){
            if(password_verify($password,$datosusuario['clave'],)){
                $data_session = [
                    'id_usuario' => $datosusuario['id'],
                    'usuario' => $datosusuario['username']
                ];
                $session = session();
                $session->set($data_session);
                return redirect()->to(base_url().'public/panel/');
            }else{
                echo "Las credenciales no son correctas";}
        }else{
            echo "Las credenciales no son correctas";
        }
    }
}