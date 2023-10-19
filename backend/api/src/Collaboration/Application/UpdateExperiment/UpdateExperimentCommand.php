<?php

declare(strict_types=1);

namespace Glance\Onboarding\Collaboration\Application\UpdateExperiment;

use Glance\Onboarding\Collaboration\Domain\IntegerId;

final class UpdateExperimentCommand
{
    private $id;
    
    public function __construct(
        integerId $id
    ) {
        $this->id = $id;
    }

    public static function fromHttpRequest(array $input, $id):self 
    {
        $command = new self(
            $id
        );

        if (array_key_exists("acronym", $input)) {
        $command->acronym = $input["acronym"];
        }

        if (array_key_exists("fullName", $input)) {
            $command->fullName = $input["fullName"];
        }

        if (array_key_exists("address", $input)) {
            $command->address = $input["address"];
        }

        return $command;
    }

    public function id(): IntegerId
    {
        return $this->id;
    }

    public function acronym(): string
    {
        return $this->acronym;
    }

    public function fullName(): string
    {
        return $this->fullName;
    }

    public function address(): string
    {
        return $this->address;
    }

}
