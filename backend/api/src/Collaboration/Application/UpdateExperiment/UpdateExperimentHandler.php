<?php

declare(strict_types=1);

namespace Glance\Onboarding\Collaboration\Application\UpdateExperiment;

use Glance\Onboarding\Collaboration\Domain\Experiment;
use Glance\Onboarding\Collaboration\Domain\ExperimentReadRepositoryInterface;
use Glance\Onboarding\Collaboration\Domain\ExperimentWriteRepositoryInterface;
use Glance\Onboarding\Collaboration\Domain\IntegerId;

final class UpdateExperimentHandler
{
    private $experimentReadRepository;
    private $experimentWriteRepository;
    private $command;

    public function __construct(
        ExperimentReadRepositoryInterface $experimentReadRepository,
        ExperimentWriteRepositoryInterface $experimentWriteRepository
    ) {
        $this->experimentReadRepository = $experimentReadRepository;
        $this->experimentWriteRepository = $experimentWriteRepository;
    }

    public function handle(UpdateExperimentCommand $command, IntegerId $id): Experiment
    {

        $this->command = $command;
        $experiment = $this->experimentReadRepository->findById($id);

        $experiment->update(
            $this->commandHasProperty("acronym") ? $command->acronym() : $experiment->acronym(),
            $this->commandHasProperty("fullName") ? $command->fullName() : $experiment->fullName(),
            $this->commandHasProperty("address") ? $command->address() : $experiment->address()
        );

        $this->experimentWriteRepository->updateExperiment($experiment);

        return $experiment;
    }

    private function commandHasProperty(string $property): bool
    {
        return property_exists($this->command, $property);
    }
}
