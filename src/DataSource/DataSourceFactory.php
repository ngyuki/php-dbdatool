<?php
namespace ngyuki\DbdaTool\DataSource;

use PDO;

class DataSourceFactory
{
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function create(string $arg): DataSourceInterface
    {
        if ($arg === '@') {
            return self::createByConfig($this->config);
        }

        if ($arg === '!') {
            return new EmptySource();
        }

        if (preg_match('/^\w\w+:/', $arg)) {
            return self::createByDsn($arg);
        }

        if (preg_match('/\.php$/', $arg)) {
            /** @noinspection PhpIncludeInspection */
            return static::createByConfig(require $arg);
        }

        return new FileSource($arg);
    }

    public static function createByConfig(array $config)
    {
        $pdo = $config['pdo'] ?? null;
        if ($pdo) {
            return new static($pdo);
        }

        $dsn = $config['dsn'];
        $username = $config['username'] ?? null;
        $password = $config['password'] ?? null;

        return new ConnectionSource(new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]));
    }

    public static function createByDsn(string $dsn)
    {
        list ($driver, $param, $username, $password) = explode(':', $dsn, 4) + [null, null, null, null];

        return new ConnectionSource(new PDO("$driver:$param", $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]));
    }
}