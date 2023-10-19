<?php

declare(strict_types=1);

namespace Glance\Onboarding\Collaboration\Infrastructure\Web;

use Exception;
use Glance\Onboarding\Collaboration\Application\GetExperimentDetails\ExperimentViewRepositoryInterface;
use Glance\Onboarding\Collaboration\Domain\ExperimentWriteRepositoryInterface;
use Glance\Onboarding\Collaboration\Application\RegisterExperiment\RegisterExperimentCommand;
use Glance\Onboarding\Collaboration\Application\RegisterExperiment\RegisterExperimentHandler;
use Glance\Onboarding\Collaboration\Domain\MemberWriteRepositoryInterface;
use Glance\Onboarding\Collaboration\Application\GetMemberDetails\MemberViewRepositoryInterface;
use Glance\Onboarding\Collaboration\Application\RegisterMember\RegisterMemberCommand;
use Glance\Onboarding\Collaboration\Application\RegisterMember\RegisterMemberHandler;
use Glance\Onboarding\Collaboration\Application\UpdateMember\UpdateMemberCommand;
use Glance\Onboarding\Collaboration\Application\UpdateMember\UpdateMemberHandler;
use Slim\Exception\HttpBadRequestException;
use Glance\Onboarding\Collaboration\Application\UpdateExperiment\UpdateExperimentCommand;
use Glance\Onboarding\Collaboration\Application\UpdateExperiment\UpdateExperimentHandler;
use Glance\Onboarding\Collaboration\Domain\IntegerId;
use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class CollaborationController
{
    private $experimentDetailsRepository;
    private $experimentWriteRepository;
    private $registerExperimentHandler;
    private $memberDetailsRepository;
    private $memberWriteRepository;
    private $registerMemberHandler;
    private $updateMemberHandler;
    private $updateExperimentHandler;

    public function __construct(
        ExperimentViewRepositoryInterface $experimentDetailsRepository,
        MemberViewRepositoryInterface $memberDetailsRepository,
        ExperimentWriteRepositoryInterface $experimentWriteRepository,
        MemberWriteRepositoryInterface $memberWriteRepository,
        RegisterExperimentHandler $registerExperimentHandler,
        RegisterMemberHandler $registerMemberHandler,
        UpdateMemberHandler $updateMemberHandler,
        UpdateExperimentHandler $updateExperimentHandler
    ) {
        $this->experimentDetailsRepository = $experimentDetailsRepository;
        $this->experimentWriteRepository = $experimentWriteRepository;
        $this->memberDetailsRepository = $memberDetailsRepository;
        $this->memberWriteRepository = $memberWriteRepository;
        $this->registerExperimentHandler = $registerExperimentHandler;
        $this->registerMemberHandler = $registerMemberHandler;
        $this->updateMemberHandler = $updateMemberHandler;
        $this->updateExperimentHandler = $updateExperimentHandler;
    }

    public function findMembers(Request $request, Response $response): Response
    {
        $response->getBody()->write(
            json_encode([
                "members" => $this->memberDetailsRepository->findAllMembers()
            ])
        );
        return $response->withHeader("content-type", "text/html");
    }

    public function findMemberById(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['id'];
        $member = $this->memberDetailsRepository->findDetailsById($id);

        if (!$member) {
            throw new HttpNotFoundException($request, "Member not found with id: {$id}");
        }
        $response->getBody()->write(
            json_encode([
                "member" => $member
            ])
        );
        return $response->withHeader("content-type", "text/html");
    }

    public function updateMember(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['id'];
        $id = IntegerId::fromInteger($id);

        $input = json_decode($request->getBody()->getContents(), true);
        
        
        $command = UpdateMemberCommand::fromHttpRequest($input["member"], $id);

        try {
            $member = $this->updateMemberHandler->handle($command, $id);
        } catch (Exception $e) {
            throw new HttpBadRequestException($request, $e->getMessage());
        }

        $response->getBody()->write(
            json_encode([
                "member" => $this->memberDetailsRepository->findDetailsById(
                    $member->id()->toInteger()
                )
            ])
        );

        return $response->withAddedHeader("Content-Type", "application/json");
    }

    public function registerMember(Request $request, Response $response): Response
    {
        $input = json_decode($request->getBody()->getContents(), true);

        $command = RegisterMemberCommand::fromHttpRequest($input["member"]);

        try {
            $member = $this->registerMemberHandler->handle($command);
        } catch (Exception $e) {
            throw new HttpBadRequestException($request, $e->getMessage());
        }

        $response->getBody()->write(
            json_encode([
                "member" => $this->memberDetailsRepository->findDetailsById(
                    $member->id()->toInteger()
                )
            ])
        );

        return $response->withAddedHeader("Content-Type", "application/json");
    }

    public function deleteMember(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['id'];

        try {
            $this->memberWriteRepository->deleteMember($id);
        } catch (Exception $e) {
            throw new HttpBadRequestException($request, $e->getMessage());
        }

        return $response->withStatus(204);
    }

    public function findExperiments(Request $request, Response $response): Response
    {
        $response->getBody()->write(
            json_encode([
                "experiments" => $this->experimentDetailsRepository->findAllExperiments()
            ])
        );

        return $response->withAddedHeader("Content-Type", "application/json");
    }

    public function findExperimentById(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['id'];

        $experiment = $this->experimentDetailsRepository->findDetailsById($id);

        if (!$experiment) {
            throw new HttpNotFoundException($request, "Experiment not found with id: {$id}");
        }

        $response->getBody()->write(json_encode(["experiment" => $experiment]));
        return $response->withAddedHeader("Content-Type", "application/json");
    }

    public function registerExperiment(Request $request, Response $response): Response
    {
        $input = json_decode($request->getBody()->getContents(), true);

        $command = RegisterExperimentCommand::fromHttpRequest($input["experiment"]);

        try {
            $experiment = $this->registerExperimentHandler->handle($command);
        } catch (Exception $e) {
            throw new HttpBadRequestException($request, $e->getMessage());
        }

        $response->getBody()->write(
            json_encode([
                "experiment" => $this->experimentDetailsRepository->findDetailsById(
                    $experiment->id()->toInteger()
                )
            ])
        );

        return $response->withAddedHeader("Content-Type", "application/json");
    }

    public function updateExperiment(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['id'];
        $id = IntegerId::fromInteger($id);

        $input = json_decode($request->getBody()->getContents(), true);

        $command = UpdateExperimentCommand::fromHttpRequest($input["experiment"], $id);

        try {
            $experiment = $this->updateExperimentHandler->handle($command, $id);
        } catch (Exception $e) {
            throw new HttpBadRequestException($request, $e->getMessage());
        }

        $response->getBody()->write(
            json_encode([
                "experiment" => $this->experimentDetailsRepository->findDetailsById(
                    $experiment->id()->toInteger()
                )
            ])
        );

        return $response->withAddedHeader("Content-Type", "application/json");
    }

    public function findMembersByExperimentId(Request $request, Response $response, array $args): Response
    {
        $experimentId = (int) $args['id'];

        $response->getBody()->write(
            json_encode([
                "members" => $this->memberDetailsRepository->findDetailsByExperimentId($experimentId)
            ])
        );
        return $response->withHeader("content-type", "text/html");
    }

    public function deleteExperiment(Request $request, Response $response, array $args): Response
    {
        $id = (int) $args['id'];

        if ($this->memberDetailsRepository->findDetailsByExperimentId($id)) {
            throw new HttpBadRequestException($request, "Experiment has members");
        }

        try {
            $this->experimentWriteRepository->deleteExperiment($id);
        } catch (Exception $e) {
            throw new HttpBadRequestException($request, $e->getMessage());
        }

        return $response->withStatus(204);
    }
}
