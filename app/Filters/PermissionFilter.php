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

        if (isset(AccessControl::modules()[$module])
            && !(new AccessControl())->canAccess($userId, $module)) {
            return $this->forbidden();
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
}
