<?php

declare(strict_types=1);

namespace Glance\Onboarding\Collaboration\Application\UpdateMember;

use Glance\Onboarding\Collaboration\Domain\IntegerId;

final class UpdateMemberCommand
{
    private $id;

    public function __construct(
        integerId $id
    ) {
        $this->id = $id;
    }

    public static function fromHttpRequest(array $input, IntegerId $id):self 
    {   

        $command = new self(
            $id
        );
        
        if (array_key_exists("age", $input)) {
        $command->age = $input["age"];
        }

        if (array_key_exists("firstName", $input)) {
            $command->firstName = $input["firstName"];
        }

        if (array_key_exists("lastName", $input)) {
            $command->lastName = $input["lastName"];
        }

        if (array_key_exists("email", $input)) {
            $command->email = $input["email"];
        }

        if (array_key_exists("experimentId", $input)) {
            $command->experimentId = integerId::fromInteger((int) $input["experimentId"]);
        }

        return $command;
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

}
