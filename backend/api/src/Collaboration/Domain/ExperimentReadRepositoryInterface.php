<?php

namespace Glance\Onboarding\Collaboration\Domain;

interface ExperimentReadRepositoryInterface
{
    public function findNextExperimentId(): int;
    public function findById(integerId $id): Experiment;
}
