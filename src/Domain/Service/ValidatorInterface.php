<?php


namespace EresNote\Domain\Service;


use EresNote\Domain\Entity\AbstractEntity;
use EresNote\Domain\Service\ValueObject\ValidatorResponseInterface;

interface ValidatorInterface
{
    public function validate(AbstractEntity $entity) : ValidatorResponseInterface;
}
