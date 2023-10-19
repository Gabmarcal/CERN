<?php

namespace Glance\Onboarding\Collaboration\Domain;

interface ExperimentWriteRepositoryInterface
{
    public function registerExperiment(Experiment $experiment): void;
    public function updateExperiment(Experiment $experiment): void;
    public function deleteExperiment(int $id): void;
}
