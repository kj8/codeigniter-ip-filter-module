<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Database\Migrations;

use CodeIgniter\Config\Factories;
use CodeIgniter\Database\Migration;
use Kj8\Module\IpFilter\Config\DbIpFilter as DbIpFilterConfig;
use Kj8\Module\IpFilter\Config\IpFilter as IpFilterConfig;

class CreateDbIpFilterTables extends Migration
{
    public function up(): void
    {
        /** @var DbIpFilterConfig $config */
        $config = Factories::config(DbIpFilterConfig::class);

        $forge = $this->forge;

        $forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'set_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'mode' => [
                'type' => 'ENUM',
                'constraint' => [
                    IpFilterConfig::MODE_ALLOW,
                    IpFilterConfig::MODE_DENY,
                ],
                'default' => IpFilterConfig::MODE_ALLOW,
            ],
        ]);

        $forge->addKey('id', true);

        $forge->addUniqueKey('set_name');

        $forge->createTable($config->setsTable, true);

        $forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'set_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
        ]);

        $forge->addKey('id', true);

        $forge->addKey('set_id');

        $forge->addForeignKey('set_id', $config->setsTable, 'id');

        $forge->addUniqueKey(['ip_address', 'set_id']);

        $forge->createTable($config->ipsTable, true);
    }

    public function down(): void
    {
        /** @var DbIpFilterConfig $config */
        $config = Factories::config(DbIpFilterConfig::class);

        $forge = $this->forge;
        $forge->dropTable($config->ipsTable, true);
        $forge->dropTable($config->setsTable, true);
    }
}
