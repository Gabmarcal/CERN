<?php

declare(strict_types=1);

namespace Glance\Onboarding\Collaboration\Infrastructure\Web;

use Exception;
use Glance\Onboarding\Collaboration\Application\GetExperimentDetails\ExperimentViewRepositoryInterface;
use Glance\Onboarding\Collaboration\Application\RegisterExperiment\RegisterExperimentCommand;
use Glance\Onboarding\Collaboration\Application\RegisterExperiment\RegisterExperimentHandler;
use Glance\Onboarding\Collaboration\Application\GetMemberDetails\MemberViewRepositoryInterface;
use Glance\Onboarding\Collaboration\Application\RegisterMember\RegisterMemberHandler;


use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class CollaborationController
{
    private $experimentDetailsRepository;
    private $registerExperimentHandler;
    private $registerMemberHandler;
    private $experimentMemberRepository;

    public function __construct(
        ExperimentViewRepositoryInterface $experimentDetailsRepository,
        RegisterExperimentHandler $registerExperimentHandler,
        MemberViewRepositoryInterface $memberDetailsRepository,
        RegisterMemberHandler $registerMemberHandler
    ) {
        $this->experimentDetailsRepository = $experimentDetailsRepository;
        $this->registerExperimentHandler = $registerExperimentHandler;
        $this->memberDetailsRepository = $memberDetailsRepository;
        $this->registerMemberHandler = $registerMemberHandler;
    }

    public function findMembers(Request $request, Response $response): Response
    {
        $members = $this->memberDetailsRepository->findAllMembers(); // Busca todos os membros usando o repositório

        # Se não encontrar nenhum membro, retorna um erro
        if (!$members) {
            throw new HttpNotFoundException($request, "No members found.");
        }

        # Se encontrar, retorna os membros
        $response->getBody()->write(
            json_encode([
                "members" => $members
            ])
        );
        
        return $response->withAddedHeader("Content-Type", "application/json");
    }

    public function findMemberById(Request $request, Response $response, array $args): Response
    {
    }

    public function updateMember(Request $request, Response $response, array $args): Response
    {
    }

    public function registerMember(Request $request, Response $response): Response
    {
    }

    public function deleteMember(Request $request, Response $response, array $args): Response
    {
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
    }

    public function findMembersByExperimentId(Request $request, Response $response, array $args): Response
    {
    }

    public function deleteExperiment(Request $request, Response $response, array $args): Response
    {
    }
}
