<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMovimientosCaja extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'fecha' => [
                'type'       => 'DATETIME',
                'null'       => false,
            ],
            'tipo' => [
                'type'       => "ENUM('ingreso','egreso')",
                'null'       => false,
            ],
            'monto' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
            ],
            'descripcion' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('movimientos_caja');
    }

    public function down()
    {
        $this->forge->dropTable('movimientos_caja');
    }
}
