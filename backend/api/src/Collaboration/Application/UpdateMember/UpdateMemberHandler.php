<?php

declare(strict_types=1);

namespace Glance\Onboarding\Collaboration\Application\UpdateMember;

use Glance\Onboarding\Collaboration\Domain\Member;
use Glance\Onboarding\Collaboration\Domain\MemberReadRepositoryInterface;
use Glance\Onboarding\Collaboration\Domain\MemberWriteRepositoryInterface;
use Glance\Onboarding\Collaboration\Domain\IntegerId;

final class UpdateMemberHandler
{
    private $memberReadRepository;
    private $memberWriteRepository;
    private $command;

    public function __construct(
        MemberReadRepositoryInterface $memberReadRepository,
        MemberWriteRepositoryInterface $memberWriteRepository
    ) {
        $this->memberReadRepository = $memberReadRepository;
        $this->memberWriteRepository = $memberWriteRepository;
    }

    public function handle(UpdateMemberCommand $command, IntegerId $id): Member
    {
        $this->command = $command;
        $member = $this->memberReadRepository->findById($id);
        

        $member->update(
            $this->commandHasProperty("firstName") ? $command->firstName() : $member->firstName(),
            $this->commandHasProperty("lastName") ? $command->lastName() : $member->lastName(),
            $this->commandHasProperty("email") ? $command->email() : $member->email(),
            $this->commandHasProperty("age") ? $command->age() : $member->age(),
            $this->commandHasProperty("experimentId") ? $command->experimentId() : $member->experimentId()
        );

        $this->memberWriteRepository->updateMember($member);

        return $member;
    }

    private function commandHasProperty(string $property): bool
    {
        return property_exists($this->command, $property);
    }
}
