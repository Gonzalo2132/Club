<?php

namespace App\Database\Seeds;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;
use DateTimeImmutable;
use RuntimeException;

class DemoSeeder extends Seeder
{
    private const TABLES_TO_CLEAR = [
        'pagos',
        'movimientos_caja',
        'gastos_caja',
        'liquidaciones_planes',
        'liquidaciones',
        'socios_tutores',
        'socios_disciplinas',
        'socios_planes',
        'socios',
        'recaudadores',
        'tutores',
        'disciplinas',
        'planes',
        'cajas',
        'zonas',
        'roles_acciones',
        'roles_modulos',
        'usuarios',
        'roles',
    ];

    public function run()
    {
        if (getenv('DEMO_RESET_CONFIRMED') !== 'club') {
            throw new RuntimeException(
                'Se rechazó el reset. Define DEMO_RESET_CONFIRMED=club para confirmar.'
            );
        }

        $database = (string) ($this->db->query('SELECT DATABASE() AS name')->getRowArray()['name'] ?? '');
        if ($database !== 'club') {
            throw new RuntimeException("Se esperaba la base 'club'; la conexión apunta a '{$database}'.");
        }

        if (!$this->db->transBegin()) {
            throw new RuntimeException('No se pudo iniciar la transacción del reset demo.');
        }

        $committed = false;
        try {
            $receiptFile = $this->writeDemoReceipt();
            foreach (self::TABLES_TO_CLEAR as $table) {
                if (!$this->db->query('DELETE FROM ' . $this->db->protectIdentifiers($table))) {
                    throw new RuntimeException("No se pudo limpiar la tabla {$table}.");
                }
            }

            $now = date('Y-m-d H:i:s');
            $today = new DateTimeImmutable('today');
            $roles = $this->createRoles();
            $users = $this->createUsers($roles, $now);
            $zones = $this->createZones($now);
            $boxes = $this->createBoxes($now);
            $collectors = $this->createCollectors($zones, $boxes, $now);
            [$plans, $sportsPlans] = $this->createPlans($now);
            $disciplines = $this->createDisciplines($now);
            $tutors = $this->createTutors($now);
            $members = $this->createMembers(
                $zones,
                $collectors,
                $plans,
                $sportsPlans,
                $disciplines,
                $tutors,
                $today,
                $now
            );

            $liquidations = $this->createLiquidations($plans, $today, $now);
            $paymentTotals = $this->createPayments($members, $liquidations, $boxes, $now, $today);
            $this->createTransfers($boxes, $paymentTotals, $now);
            $this->createExpenses($boxes, $paymentTotals, $users, $now, $today, $receiptFile);
            if (!$this->db->transStatus()) {
                throw new RuntimeException('Falló la carga demo; se revertirá el reset.');
            }
            if (!$this->db->transCommit()) {
                throw new RuntimeException('No se pudo confirmar la carga demo.');
            }

            $committed = true;
        } finally {
            if (!$committed) {
                $this->db->transRollback();
            }
        }

        CLI::write('Base club reemplazada por datos ficticios de demostración.', 'green');
        CLI::write('Socios: ' . count($members) . ' | Planes: ' . count($plans) . ' | Liquidaciones: ' . count($liquidations));
        CLI::write('Usuarios disponibles: demo_admin, demo_tesoreria y demo_consulta');
        CLI::write('Contraseña compartida: ' . $users['password'], 'yellow');
    }

    private function createRoles(): array
    {
        $definitions = [
            'Administrador' => true,
            'Tesorería' => false,
            'Consulta' => false,
        ];
        $roles = [];
        foreach ($definitions as $name => $isAdmin) {
            $roles[$name] = $this->insertRow('roles', [
                'nombre' => $name,
                'es_administrador' => $isAdmin ? 1 : 0,
            ]);
        }

        $allModules = [
            'zonas',
            'usuarios',
            'socios',
            'cajas',
            'recaudadores',
            'liquidaciones',
            'pagos',
            'planes',
            'disciplinas',
        ];
        $permissions = [];
        $moduleRows = [];
        foreach ($allModules as $module) {
            $moduleRows[] = ['rol_id' => $roles['Administrador'], 'modulo' => $module];
            foreach (['ver', 'crear', 'editar', 'eliminar'] as $action) {
                $permissions[] = [
                    'rol_id' => $roles['Administrador'],
                    'modulo' => $module,
                    'accion' => $action,
                ];
            }
        }

        $treasuryPermissions = [
            'zonas' => ['ver'],
            'socios' => ['ver'],
            'cajas' => ['ver', 'crear', 'editar'],
            'recaudadores' => ['ver'],
            'liquidaciones' => ['ver'],
            'pagos' => ['ver', 'crear'],
            'planes' => ['ver'],
            'disciplinas' => ['ver'],
        ];
        foreach ($treasuryPermissions as $module => $actions) {
            $moduleRows[] = ['rol_id' => $roles['Tesorería'], 'modulo' => $module];
            foreach ($actions as $action) {
                $permissions[] = [
                    'rol_id' => $roles['Tesorería'],
                    'modulo' => $module,
                    'accion' => $action,
                ];
            }
        }

        foreach ($allModules as $module) {
            $moduleRows[] = ['rol_id' => $roles['Consulta'], 'modulo' => $module];
            $permissions[] = [
                'rol_id' => $roles['Consulta'],
                'modulo' => $module,
                'accion' => 'ver',
            ];
        }

        $this->insertRows('roles_modulos', $moduleRows);
        $this->insertRows('roles_acciones', $permissions);

        return $roles;
    }

    private function createUsers(array $roles, string $now): array
    {
        $password = 'Demo-' . strtoupper(bin2hex(random_bytes(6)));
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $accounts = [
            ['username' => 'demo_admin', 'nombre' => 'Admin', 'apellido' => 'Demo', 'dni' => 'DEMO-U-001', 'rol' => 'Administrador'],
            ['username' => 'demo_tesoreria', 'nombre' => 'Tesorería', 'apellido' => 'Demo', 'dni' => 'DEMO-U-002', 'rol' => 'Tesorería'],
            ['username' => 'demo_consulta', 'nombre' => 'Consulta', 'apellido' => 'Demo', 'dni' => 'DEMO-U-003', 'rol' => 'Consulta'],
        ];
        $users = ['password' => $password];

        foreach ($accounts as $account) {
            $users[$account['username']] = $this->insertRow('usuarios', [
                'nombre' => $account['nombre'],
                'apellido' => $account['apellido'],
                'username' => $account['username'],
                'clave' => $passwordHash,
                'dni' => $account['dni'],
                'activo' => 1,
                'fecha_alta' => $now,
                'fecha_edicion' => $now,
                'fecha_borrado' => null,
                'rol_id' => $roles[$account['rol']],
            ]);
        }

        return $users;
    }

    private function createZones(string $now): array
    {
        $zones = [];
        foreach (['Centro', 'Norte', 'Sur', 'Sede deportiva'] as $name) {
            $zones[$name] = $this->insertRow('zonas', [
                'denominacion' => $name,
                'activo' => 1,
                'fecha_alta' => $now,
                'fecha_edicion' => $now,
                'fecha_borrado' => null,
            ]);
        }

        return $zones;
    }

    private function createBoxes(string $now): array
    {
        $definitions = [
            ['Caja Central', 'Administración central', 'DEMO-CAJA-01'],
            ['Caja Norte', 'Sede de zona norte', 'DEMO-CAJA-02'],
            ['Caja Sur', 'Sede de zona sur', 'DEMO-CAJA-03'],
            ['Caja Deportiva', 'Actividades deportivas', 'DEMO-CAJA-04'],
        ];
        $boxes = [];
        foreach ($definitions as [$name, $description, $code]) {
            $boxes[] = [
                'id' => $this->insertRow('cajas', [
                    'denominacion' => $name,
                    'descripcion' => $description,
                    'codigointerno' => $code,
                    'activo' => 1,
                    'fecha_alta' => $now,
                    'fecha_edicion' => $now,
                    'fecha_borrado' => null,
                ]),
                'nombre' => $name,
            ];
        }

        return $boxes;
    }

    private function createCollectors(array $zones, array $boxes, string $now): array
    {
        $zoneNames = array_keys($zones);
        $collectors = [];
        foreach ($zoneNames as $index => $zoneName) {
            $collectors[$zones[$zoneName]] = $this->insertRow('recaudadores', [
                'nombre' => ['María', 'Diego', 'Lucía', 'Tomás'][$index],
                'apellido' => ['Demo', 'Muestra', 'Prueba', 'Ficticio'][$index],
                'dni' => sprintf('DEMO-R-%04d', $index + 1),
                'id_caja' => $boxes[$index]['id'],
                'activo' => 1,
                'fecha_alta' => $now,
                'fecha_edicion' => $now,
                'fecha_borrado' => null,
                'telefono' => sprintf('000-000-%04d', $index + 1),
                'id_zona' => $zones[$zoneName],
            ]);
        }

        return $collectors;
    }

    private function createPlans(string $now): array
    {
        $definitions = [
            ['Cuota social', 12000.00, 'Abono social mensual.'],
            ['Fútbol', 18500.00, 'Actividad deportiva de fútbol.'],
            ['Básquet', 16500.00, 'Actividad deportiva de básquet.'],
            ['Vóley', 15000.00, 'Actividad deportiva de vóley.'],
        ];
        $plans = [];
        $sportsPlans = [];
        foreach ($definitions as $index => [$name, $amount, $description]) {
            $id = $this->insertRow('planes', [
                'nombre' => $name,
                'descripcion' => $description,
                'monto' => $amount,
                'activo' => 1,
                'fecha_alta' => $now,
                'fecha_edicion' => $now,
                'fecha_borrado' => null,
            ]);
            $plans[$id] = ['id' => $id, 'nombre' => $name, 'monto' => $amount];
            if ($index > 0) {
                $sportsPlans[] = $id;
            }
        }

        return [$plans, $sportsPlans];
    }

    private function createDisciplines(string $now): array
    {
        $disciplines = [];
        foreach (['Fútbol', 'Básquet', 'Vóley'] as $name) {
            $disciplines[$name] = $this->insertRow('disciplinas', [
                'nombre' => $name,
                'descripcion' => 'Actividad de demostración.',
                'activo' => 1,
                'fecha_alta' => $now,
                'fecha_edicion' => $now,
                'fecha_borrado' => null,
            ]);
        }

        return $disciplines;
    }

    private function createTutors(string $now): array
    {
        $firstNames = ['Carolina', 'Pablo', 'Andrea', 'Sergio', 'Natalia', 'Hugo', 'Mónica', 'Raúl'];
        $lastNames = ['Demo', 'Muestra', 'Ejemplo', 'Ficticio', 'Prueba', 'Simulado', 'Modelo', 'Inventado'];
        $tutors = [];
        for ($index = 0; $index < 8; $index++) {
            $tutors[] = $this->insertRow('tutores', [
                'nombre' => $firstNames[$index],
                'apellido' => $lastNames[$index],
                'dni' => sprintf('DEMO-T-%04d', $index + 1),
                'telefono' => sprintf('000-100-%04d', $index + 1),
                'email' => sprintf('tutor%02d@example.invalid', $index + 1),
                'parentesco' => $index % 2 === 0 ? 'Madre' : 'Padre',
                'fecha_alta' => $now,
                'fecha_edicion' => $now,
            ]);
        }

        return $tutors;
    }

    private function createMembers(
        array $zones,
        array $collectors,
        array $plans,
        array $sportsPlans,
        array $disciplines,
        array $tutors,
        DateTimeImmutable $today,
        string $now
    ): array {
        $firstNames = ['Lucía', 'Mateo', 'Sofía', 'Benjamín', 'Valentina', 'Joaquín', 'Martina', 'Thiago', 'Camila', 'Bruno', 'Emma', 'Lautaro', 'Mora', 'Felipe', 'Renata'];
        $lastNames = ['Gómez', 'Pérez', 'Fernández', 'López', 'Martínez', 'Díaz', 'Romero', 'Alonso', 'Torres', 'Navarro', 'Silva', 'Rojas', 'Molina', 'Castro', 'Acosta'];
        $zoneIds = array_values($zones);
        $sportPlanNames = ['Fútbol', 'Básquet', 'Vóley'];
        $members = [];
        $relationPlans = [];
        $relationDisciplines = [];
        $relationTutors = [];

        for ($index = 0; $index < 30; $index++) {
            $zoneId = $zoneIds[$index % count($zoneIds)];
            $sportIndex = random_int(0, count($sportsPlans) - 1);
            $sportPlanId = $sportsPlans[$sportIndex];
            $age = random_int(8, 67);
            $tutorId = $age < 18 ? $tutors[$index % count($tutors)] : null;
            $firstName = $firstNames[random_int(0, count($firstNames) - 1)];
            $lastName = $lastNames[random_int(0, count($lastNames) - 1)];
            $memberId = $this->insertRow('socios', [
                'nombre' => $firstName,
                'apellido' => $lastName,
                'id_plan' => array_key_first($plans),
                'domicilio' => sprintf('Calle de muestra %d', random_int(10, 990)),
                'telefono' => sprintf('000-200-%04d', $index + 1),
                'fecha_nac' => $today->modify("-{$age} years")->modify('-' . random_int(0, 330) . ' days')->format('Y-m-d'),
                'dni' => sprintf('DEMO-S-%05d', $index + 1),
                'email' => sprintf('socio%02d@example.invalid', $index + 1),
                'id_zona' => $zoneId,
                'activo' => 1,
                'fecha_alta' => $today->modify('-' . random_int(1, 5) . ' years')->format('Y-m-d H:i:s'),
                'fecha_edicion' => $now,
                'fecha_borrado' => null,
                'id_tutor' => $tutorId,
                'id_recaudador' => $collectors[$zoneId],
            ]);

            $memberPlanIds = [array_key_first($plans), $sportPlanId];
            if ($index % 7 === 0) {
                $additionalSportPlan = $sportsPlans[($sportIndex + 1) % count($sportsPlans)];
                $memberPlanIds[] = $additionalSportPlan;
            }

            foreach (array_unique($memberPlanIds) as $planId) {
                $relationPlans[] = [
                    'id_socio' => $memberId,
                    'id_plan' => $planId,
                    'fecha_borrado' => null,
                ];
            }

            $disciplineName = $sportPlanNames[$sportIndex];
            $relationDisciplines[] = [
                'id_socio' => $memberId,
                'id_disciplina' => $disciplines[$disciplineName],
                'fecha_alta' => $now,
                'fecha_borrado' => null,
            ];

            if ($index % 7 === 0) {
                $additionalSportIndex = ($sportIndex + 1) % count($sportsPlans);
                $relationDisciplines[] = [
                    'id_socio' => $memberId,
                    'id_disciplina' => $disciplines[$sportPlanNames[$additionalSportIndex]],
                    'fecha_alta' => $now,
                    'fecha_borrado' => null,
                ];
            }

            if ($tutorId !== null) {
                $relationTutors[] = [
                    'id_socio' => $memberId,
                    'id_tutor' => $tutorId,
                    'fecha_borrado' => null,
                ];
            }

            $members[] = [
                'id' => $memberId,
                'nombre' => $firstName,
                'apellido' => $lastName,
                'zona_id' => $zoneId,
                'recaudador_id' => $collectors[$zoneId],
                'plan_ids' => array_unique($memberPlanIds),
                'index' => $index,
            ];
        }

        $this->insertRows('socios_planes', $relationPlans);
        $this->insertRows('socios_disciplinas', $relationDisciplines);
        $this->insertRows('socios_tutores', $relationTutors);

        return $members;
    }

    private function createLiquidations(array $plans, DateTimeImmutable $today, string $now): array
    {
        $periods = [
            ['label' => 'Vencida', 'offset' => -15],
            ['label' => 'Próxima', 'offset' => 5],
            ['label' => 'Futura', 'offset' => 22],
        ];
        $liquidations = [];
        $relations = [];

        foreach ($plans as $plan) {
            foreach ($periods as $periodIndex => $period) {
                $dueDate = $today->modify(($period['offset'] >= 0 ? '+' : '') . $period['offset'] . ' days');
                $id = $this->insertRow('liquidaciones', [
                    'nombre' => sprintf('%s - %s %s', $period['label'], $plan['nombre'], $dueDate->format('m/Y')),
                    'monto' => $plan['monto'],
                    'fecha_vencimiento' => $dueDate->format('Y-m-d'),
                    'activo' => 1,
                    'fecha_alta' => $now,
                    'fecha_edicion' => $now,
                    'fecha_borrado' => null,
                    'id_plan' => $plan['id'],
                    'nombre_plan' => $plan['nombre'],
                ]);
                $relations[] = [
                    'id_liquidacion' => $id,
                    'id_plan' => $plan['id'],
                    'monto' => $plan['monto'],
                ];
                $liquidations[] = [
                    'id' => $id,
                    'nombre' => sprintf('%s - %s %s', $period['label'], $plan['nombre'], $dueDate->format('m/Y')),
                    'monto' => $plan['monto'],
                    'fecha_vencimiento' => $dueDate->format('Y-m-d'),
                    'plan_id' => $plan['id'],
                    'period_index' => $periodIndex,
                ];
            }
        }

        $this->insertRows('liquidaciones_planes', $relations);

        return $liquidations;
    }

    private function createPayments(
        array $members,
        array $liquidations,
        array $boxes,
        string $now,
        DateTimeImmutable $today
    ): array {
        $paymentTotals = [];
        foreach ($boxes as $box) {
            $paymentTotals[$box['id']] = 0.0;
        }
        $paymentMethods = ['Efectivo', 'Transferencia', 'Débito'];

        foreach ($members as $member) {
            foreach ($liquidations as $liquidation) {
                if (!in_array($liquidation['plan_id'], $member['plan_ids'], true)) {
                    continue;
                }

                $scenario = ($member['index'] + $liquidation['period_index']) % 4;
                if ($scenario === 2) {
                    continue;
                }

                $total = (float) $liquidation['monto'];
                $isPartial = $scenario === 1;
                $paid = $isPartial
                    ? round($total * random_int(35, 70) / 100, 2)
                    : $total;
                $installments = !$isPartial && $member['index'] % 3 === 0
                    ? [round($paid / 2, 2), round($paid - round($paid / 2, 2), 2)]
                    : [$paid];
                $boxId = $boxes[$member['index'] % count($boxes)]['id'];

                foreach ($installments as $installmentIndex => $amount) {
                    $paymentDate = new DateTimeImmutable($liquidation['fecha_vencimiento']);
                    if ($paymentDate > $today) {
                        $paymentDate = $today;
                    } else {
                        $paymentDate = $paymentDate->modify('-' . random_int(0, 4) . ' days');
                    }
                    if ($installmentIndex > 0) {
                        $paymentDate = $paymentDate->modify('+' . random_int(1, 3) . ' days');
                        if ($paymentDate > $today) {
                            $paymentDate = $today;
                        }
                    }
                    $dateTime = $paymentDate->setTime(random_int(9, 18), random_int(0, 59))->format('Y-m-d H:i:s');
                    $method = $paymentMethods[random_int(0, count($paymentMethods) - 1)];
                    $payment = [
                        'id_socio' => $member['id'],
                        'id_liquidacion' => $liquidation['id'],
                        'id_caja' => $boxId,
                        'forma_pago' => $method,
                        'monto' => $amount,
                        'fecha_pago' => $dateTime,
                        'fecha_alta' => $now,
                        'fecha_edicion' => $now,
                        'fecha_borrado' => null,
                        'id_recaudador' => $member['recaudador_id'],
                    ];
                    $this->insertRow('pagos', $payment);
                    $paymentTotals[$boxId] += (float) $amount;

                    $boxName = $boxes[$member['index'] % count($boxes)]['nombre'];
                    $concept = sprintf(
                        '%s %s -> %s | Pago: %s - %s | Socio: %s %s | Forma: %s',
                        $member['apellido'],
                        $member['nombre'],
                        $boxName,
                        $liquidation['nombre'],
                        number_format($amount, 2, '.', ''),
                        $member['apellido'],
                        $member['nombre'],
                        $method
                    );
                    $this->insertRow('movimientos_caja', [
                        'caja_origen_id' => null,
                        'caja_destino_id' => $boxId,
                        'tipo_movimiento' => 'ingreso',
                        'monto' => $amount,
                        'concepto' => substr($concept, 0, 255),
                        'fecha' => $dateTime,
                    ]);
                }
            }
        }

        return $paymentTotals;
    }

    private function createTransfers(array $boxes, array $paymentTotals, string $now): void
    {
        $transfers = [
            [0, 1, 5000.00, 'Reposición de cambio de caja'],
            [0, 2, 3500.00, 'Distribución para gastos de sede'],
            [0, 3, 2500.00, 'Fondo para actividades deportivas'],
        ];
        foreach ($transfers as [$originIndex, $destinationIndex, $amount, $reason]) {
            $origin = $boxes[$originIndex];
            $destination = $boxes[$destinationIndex];
            $transferAmount = min($amount, max(0, (float) $paymentTotals[$origin['id']] / 20));
            if ($transferAmount <= 0) {
                continue;
            }

            $this->insertRow('movimientos_caja', [
                'caja_origen_id' => $origin['id'],
                'caja_destino_id' => $destination['id'],
                'tipo_movimiento' => 'transferencia',
                'monto' => round($transferAmount, 2),
                'concepto' => sprintf('%s -> %s | %s', $origin['nombre'], $destination['nombre'], $reason),
                'fecha' => $now,
            ]);
        }

    }

    private function createExpenses(
        array $boxes,
        array $paymentTotals,
        array $users,
        string $now,
        DateTimeImmutable $today,
        string $receiptFile
    ): void {
        $definitions = [
            ['servicios', 'Servicio de electricidad', 'Cooperativa local', 0, false],
            ['materiales', 'Elementos para entrenamiento', 'Comercial Demo', 3, false],
            ['limpieza', 'Insumos de limpieza', 'Proveeduría de muestra', 1, false],
            ['reparaciones', 'Reparación de vestuario', 'Taller de ejemplo', 2, false],
            ['transporte', 'Traslado de divisiones formativas', 'Transporte Demo', 3, true],
            ['otros', 'Compra duplicada anulada', 'Proveedor de prueba', 0, true],
            ['servicios', 'Servicio de internet', 'Conectividad Demo', 0, false],
            ['materiales', 'Material para mantenimiento', 'Ferretería de muestra', 2, false],
        ];
        foreach ($definitions as $index => [$category, $concept, $payee, $boxIndex, $annulled]) {
            $box = $boxes[$boxIndex];
            $amount = min(7500 + ($index * 900), max(500, (float) $paymentTotals[$box['id']] / 12));
            $expense = [
                'id_caja' => $box['id'],
                'categoria' => $category,
                'concepto' => $concept,
                'destinatario' => $payee,
                'monto' => round($amount, 2),
                'fecha_gasto' => $today->modify('-' . ($index + 1) . ' days')->format('Y-m-d'),
                'comprobante' => $index === 0 ? $receiptFile : null,
                'registrado_por' => $users['demo_tesoreria'],
                'fecha_alta' => $now,
                'anulado_por' => $annulled ? $users['demo_admin'] : null,
                'fecha_anulacion' => $annulled ? $now : null,
                'motivo_anulacion' => $annulled ? 'Registro ficticio anulado para mostrar el historial.' : null,
            ];
            $this->insertRow('gastos_caja', $expense);
        }
    }

    private function writeDemoReceipt(): string
    {
        $directory = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'gastos';
        if (!is_dir($directory) && !mkdir($directory, 0750, true) && !is_dir($directory)) {
            throw new RuntimeException('No se pudo crear el directorio privado de comprobantes demo.');
        }

        $filename = 'demo-recibo-' . strtoupper(bin2hex(random_bytes(5))) . '.pdf';
        $escapeText = static function (string $text): string {
            return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
        };
        $lines = [
            'COMPROBANTE DE DEMOSTRACION',
            'Club Atletico Sarmiento',
            'Operacion ficticia - sin validez fiscal',
            'Archivo generado para mostrar el sistema',
        ];
        $commands = ['BT', '/F1 20 Tf', '50 740 Td', '(' . $escapeText($lines[0]) . ') Tj', '/F1 12 Tf'];
        foreach (array_slice($lines, 1) as $line) {
            $commands[] = '0 -28 Td';
            $commands[] = '(' . $escapeText($line) . ') Tj';
        }
        $commands[] = 'ET';
        $stream = implode("\n", $commands) . "\n";

        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            '<< /Length ' . strlen($stream) . " >>\nstream\n" . $stream . 'endstream',
        ];
        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($index + 1) . " 0 obj\n" . $object . "\nendobj\n";
        }
        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 6\n0000000000 65535 f \n";
        for ($index = 1; $index <= count($objects); $index++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$index]);
        }
        $pdf .= "trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n" . $xrefOffset . "\n%%EOF\n";

        $path = $directory . DIRECTORY_SEPARATOR . $filename;
        if (file_put_contents($path, $pdf, LOCK_EX) !== strlen($pdf)) {
            throw new RuntimeException('No se pudo escribir el comprobante PDF ficticio.');
        }

        return $filename;
    }

    private function insertRow(string $table, array $data): int
    {
        if (!$this->db->table($table)->insert($data)) {
            throw new RuntimeException("No se pudo insertar un registro en {$table}.");
        }

        return (int) $this->db->insertID();
    }

    private function insertRows(string $table, array $rows): void
    {
        if ($rows !== [] && !$this->db->table($table)->insertBatch($rows)) {
            throw new RuntimeException("No se pudo insertar el lote en {$table}.");
        }
    }
}
