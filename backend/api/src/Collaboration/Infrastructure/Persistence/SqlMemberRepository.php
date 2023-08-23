<?php

declare(strict_types=1);

namespace Glance\Onboarding\Collaboration\Infrastructure\Persistence;

use Doctrine\DBAL\Driver\Connection;

class SqlMemberRepository

{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findAllMembers(): array
    {
        $query = "SELECT
                E.ID AS ID,
                E.ACRONYM AS ACRONYM,
                E.FULL_NAME AS FULL_NAME,
                E.ADDRESS AS ADDRESS
            FROM MEMBER E
        ";
        $statement = $this->connection->prepare($query);
        $statement->execute();

        return array_map(function (array $row) {
            return MemberDetails::fromPersistence($row);
        }, $statement->fetchAllAssociative());
    }

}
