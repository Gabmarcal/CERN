<?php

declare(strict_types=1);

namespace Glance\Onboarding\Collaboration\Application\GetMemberDetails;

class Member implements \JsonSerializable
{
    private $id;
    private $firstName;
    private $lastName;
    private $email;
    private $age;
    private $experimentId;
    private $experimentName;

    public function __construct(
        int $id,
        string $firstName,
        string $lastName,
        string $email,
        string $age,
        string $experimentId,
        string $experimentName
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->age = $age;
        $this->experimentId = $experimentId;
        $this->experimentName = $experimentName;
    }

    public static function fromPersistence(array $data): self
    {
        return new self(
            (int) $data['ID'],
            $data['FIRST_NAME'],
            $data['LAST_NAME'],
            $data['EMAIL'],
            $data['AGE'],
            $data['EXPERIMENT_ID'],
            $data['EXPERIMENT_NAME']
        );
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "firstName" => $this->firstName,
            "lastName" => $this->lastName,
            "email" => $this->email,
            "age" => $this->age,
            "experimentId" => $this->experimentId,
            "experimentName" => $this->experimentName

        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
