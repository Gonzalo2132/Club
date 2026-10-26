<?php

namespace App\Filters;

use App\Libraries\AccessControl;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $userId = (int) $session->get('id_usuario');

        if ($userId < 1) {
            return redirect()->to(base_url('public/login'));
        }

        $segments = $request->getUri()->getSegments();
        $module = strtolower($segments[0] ?? '');

        if ($module === 'roles') {
            if (!(new AccessControl())->isAdministrator($userId)) {
                return $this->forbidden();
            }

            return null;
        }

        if ($module === '' || $module === 'panel') {
            return null;
        }

        if (isset(AccessControl::modules()[$module])) {
            $action = $this->actionForRequest($request, $segments);
            if (!(new AccessControl())->canPerform($userId, $module, $action)) {
                return $this->forbidden();
            }
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }

    private function forbidden()
    {
        return service('response')
            ->setStatusCode(403)
            ->setBody('No tienes permiso para acceder a este módulo.');
    }

    private function actionForRequest(RequestInterface $request, array $segments): string
    {
        $endpoint = strtolower($segments[1] ?? '');
        $method = strtoupper($request->getMethod());

        if (in_array($endpoint, ['borrar', 'eliminar'], true)) {
            return 'eliminar';
        }
        if (in_array($endpoint, ['nuevo', 'guardar', 'guardar-movimiento'], true)) {
            return 'crear';
        }
        if (in_array($endpoint, ['editar', 'actualizar', 'asignar-rol'], true)) {
            return 'editar';
        }

        if ($method === 'POST') {
            return 'crear';
        }

        return 'ver';
    }
}
