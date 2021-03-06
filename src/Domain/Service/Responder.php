<?php

namespace EresNote\Domain\Service;

use EresNote\Domain\Entity\AbstractEntity;
use EresNote\Domain\Repository\RepositoryInterface;
use EresNote\Domain\Service\Factory\HttpResponseFactoryInterface;
use EresNote\Domain\Service\ValueObject\SimpleHttpResponseInterface;

class Responder implements ResponderInterface
{
    private $validator;
    private $repository;
    private $httpResponseFactory;

    public function __construct(
        ValidatorInterface $validator,
        RepositoryInterface $repository,
        HttpResponseFactoryInterface $httpResponseFactory
    ){
        $this->validator = $validator;
        $this->repository = $repository;
        $this->httpResponseFactory = $httpResponseFactory;
    }

    public function prepare(AbstractEntity $entity) : SimpleHttpResponseInterface
    {
        $validatorResponse = $this->validator->validate($entity);
        if ($validatorResponse->isValid()) {

            $this->repository->persist($entity);

            return $this->getSuccessResponse($entity);
        }
        return $this->getFailureResponse($validatorResponse->getErrors());
    }

    protected function getSuccessResponse(AbstractEntity $entity): SimpleHttpResponseInterface
    {
        $response = $this->httpResponseFactory->create(200, $entity);
        return $response;
    }

    protected function getFailureResponse(array $errors): SimpleHttpResponseInterface
    {
        $response = $this->httpResponseFactory->create(422, $errors);
        return $response;
    }
}
