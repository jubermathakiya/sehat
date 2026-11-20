<?php

namespace App\Services\Tenant;

use App\Models\Central\Customer;
use App\Models\Central\TenantDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TenantConnectionManager
{
    protected static ?Customer $current = null;

    public function connect(Customer $customer): void
    {
        /** @var TenantDatabase|null $db */
        $db = $customer->database;

        if (! $db || $db->status !== 'ready') {
            throw new \RuntimeException('Tenant database is not ready for customer #'.$customer->id);
        }

        $this->configure($db->db_host, (int) $db->db_port, $db->db_name, $db->db_username, $db->decrypted_password);
        self::$current = $customer;
    }

    public function configure(string $host, int $port, string $database, string $username, string $password): void
    {
        Config::set('database.connections.tenant', [
            'driver' => 'mysql',
            'host' => $host,
            'port' => $port,
            'database' => $database,
            'username' => $username,
            'password' => $password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');
    }

    public function disconnect(): void
    {
        DB::purge('tenant');
        self::$current = null;
    }

    public static function current(): ?Customer
    {
        return self::$current;
    }

    public static function setCurrent(?Customer $customer): void
    {
        self::$current = $customer;
    }
}
