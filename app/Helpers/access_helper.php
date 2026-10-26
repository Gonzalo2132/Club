<?php

if (!function_exists('can_action')) {
    function can_action(string $module, string $action): bool
    {
        static $allowedActionsByUser = [];

        $userId = (int) session()->get('id_usuario');
        if (!array_key_exists($userId, $allowedActionsByUser)) {
            $allowedActionsByUser[$userId] = (new \App\Libraries\AccessControl())
                ->allowedActions($userId);
        }

        return in_array($action, $allowedActionsByUser[$userId][$module] ?? [], true);
    }
}
