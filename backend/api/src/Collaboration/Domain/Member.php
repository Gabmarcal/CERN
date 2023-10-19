<?php

declare(strict_types=1);

namespace Glance\Onboarding\Collaboration\Domain;

use Glance\Onboarding\Collaboration\Domain\Exception\MemberException;
use Glance\Onboarding\Collaboration\Domain\IntegerId;

class Member
{
    private $id;
    private $firstName;
    private $lastName;
    private $email;
    private $age;
    private $experimentId;

    public function __construct(
        integerId $id,
        string $firstName,
        string $lastName,
        string $email,
        string $age,
        IntegerId $experimentId
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->age = $age;
        $this->experimentId = $experimentId;
    }

    public static function fromPersistence(?array $data): self
    {
        if (empty($data)) {
            throw MemberException::memberNotFound();
        }

        return new self(
            IntegerId::fromString($data['ID']),
            $data['FIRST_NAME'],
            $data['LAST_NAME'],
            $data['EMAIL'],
            $data['AGE'],
            IntegerId::fromString($data['EXPERIMENT_ID'])
        );
    }

    public static function create(
        integerId $id,
        string $firstName,
        string $lastName,
        string $email,
        string $age,
        IntegerId $experimentId
    ): self {
        return new self(
            $id,
            $firstName,
            $lastName,
            $email,
            $age,
            $experimentId

        );
    }

    public function update(
        string $firstName,
        string $lastName,
        string $email,
        string $age,
        IntegerId $experimentId
    ):void
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->age = $age;
        $this->experimentId = $experimentId;
    }

    public function id(): IntegerId
    {
        return $this->id;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function age(): string
    {
        return $this->age;
    }

    public function experimentId(): IntegerId
    {
        return $this->experimentId;
    }


    public function updateFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function updateLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function updateEmail(string $email): void
    {
        $this->email = $email;
    }
    
    public function updateAge(string $age): void
    {
        $this->age = $age;
    }

    public function updateExperimentId(IntegerId $experimentId): void
    {
        $this->experimentId = $experimentId;
    }
}
