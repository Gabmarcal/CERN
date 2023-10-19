<?php

declare(strict_types=1);

namespace Glance\Onboarding\Collaboration\Application\GetMemberDetails;

interface MemberViewRepositoryInterface
{
    public function findAllMembers(): array;
    public function findDetailsById(int $id): ?Member;
    public function findDetailsByExperimentId(int $experimentId): array;
}
