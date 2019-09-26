<?php

namespace Tests\DataFixtures\Helper;

use Doctrine\ORM\EntityManagerInterface;

class IdGenerator
{
    public static function setIdToEntity(EntityManagerInterface $manager, $entity, $id = null)
    {
        $className = get_class($entity);

        $idRef = new \ReflectionProperty($className, "id");
        $idRef->setAccessible(true);
        $idRef->setValue($entity, $id);

        $metadata = $manager->getClassMetadata($className);
        $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

        return $entity;
    }
}
