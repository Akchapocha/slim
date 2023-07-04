<?php

namespace PriNikApp\FrontTest\Infrastructure;

use PDO;
use PDOStatement;

/**
 *
 */
class Database
{
    /**
     * @var PDO
     */
    private PDO $connection;

    /**
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $statement
     * @param array  $params
     *
     * @return array
     */
    public function fetchAll(string $statement, array $params = []): array
    {
        return $this->executeStatement($statement, $params)->fetchAll();
    }

    /**
     * @param string $statement
     * @param array $params
     * @return string
     */
    public function fetchColumn(string $statement, array $params = []): string
    {
        return (string) $this->executeStatement($statement, $params)->fetchColumn() ?? "";
    }

    /**
     * @param string $statement
     * @param array $params
     * @return PDOStatement
     */
    private function executeStatement(string $statement, array $params = []): PDOStatement
    {

        $statement = $this->bindParams($statement, $params);
        $statement->execute();
        return $statement;
    }

    /**
     * @param string $statement
     * @param array $params
     * @return PDOStatement
     */
    private function bindParams(string $statement, array $params = []): PDOStatement
    {
        $statement = $this->connection->prepare($statement);
        foreach ($params as $param => $value) {
            if (is_array($value)) {
                $var = $value['var'] ?? $value[0];
                $type = $value["type"] ?? $value[1];
            } else
                $var = $value;
            $statement->bindParam($param, $var, $type ?? PDO::PARAM_STR);
        }

        return $statement;
    }
}