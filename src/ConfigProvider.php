<?php

namespace Harmony\DAL\Illuminate;

use Harmony\Config;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\PostgresConnection;
use Illuminate\Database\SQLiteConnection;
use Psr\Container\ContainerInterface;

final class ConfigProvider
{
    public function getConfig(string $dbConfigRoot): array
    {
        return [
            ConnectionInterface::class => \DI\factory(function (ContainerInterface $container) use ($dbConfigRoot) {
                $config = $container->get(Config::class);

                $dsn = $config->get("$dbConfigRoot.dsn");

                if (substr($dsn, 0, 5) === 'mysql') {
                    return new MySqlConnection($container->get(\PDO::class));
                }

                if (substr($dsn, 0, 6) === 'sqlite') {
                    return new SQLiteConnection($container->get(\PDO::class));
                }

                if (substr($dsn, 0, 8) === 'postgres') {
                    return new PostgresConnection($container->get(\PDO::class));
                }

                throw new \RuntimeException("Unrecognised DSN {$dsn}");
            }),

            \PDO::class => \DI\factory(function (ContainerInterface $container) use ($dbConfigRoot) {
                $config = $container->get(Config::class);

                $pdo = new \PDO(
                    $config->get("$dbConfigRoot.dsn"),
                    $config->get("$dbConfigRoot.user"),
                    $config->get("$dbConfigRoot.pass")
                );

                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                return $pdo;
            })
        ];
    }
}
