<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EliminarAuditoriaFinanciera extends Migration
{
    public function up()
    {
        $this->forge->dropTable('audit_events', true);
    }

    public function down()
    {
        if ($this->db->tableExists('audit_events')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'actor_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'actor_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'event_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 60,
            ],
            'entity_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 40,
            ],
            'entity_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'summary' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'before_data' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'after_data' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'reason' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['created_at', 'id']);
        $this->forge->addKey(['actor_id', 'created_at']);
        $this->forge->addKey(['entity_type', 'entity_id']);
        $this->forge->createTable('audit_events');
    }
}
