<?php

namespace G4\DataRepository;

use G4\DataRepository\Exception\MissingActionException;
use G4\DataRepository\Exception\MissingIdentityException;
use G4\DataRepository\Exception\MissingMapperException;

class Command implements CommandInterface
{
    const ACTION_UPSERT = 'upsert';
    const ACTION_INSERT = 'insert';
    const ACTION_DELETE = 'delete';
    const ACTION_UPDATE = 'update';

    /**
     * @var \G4\DataMapper\Common\MappingInterface
     */
    private $map;
    private $key;

    /**
     * @var \G4\DataMapper\Common\IdentityInterface
     */
    private $identity;

    private $action;

    /**
     * @return \G4\DataMapper\Common\MappingInterface
     */
    public function getMap()
    {
        if(!$this->map instanceof \G4\DataMapper\Common\MappingInterface){
            throw new MissingMapperException();
        }
        return $this->map;
    }

    /**
     * @return  \G4\DataMapper\Common\IdentityInterface
     */
    public function getIdentity()
    {
        if(!$this->identity instanceof \G4\DataMapper\Common\IdentityInterface){
            throw new MissingIdentityException();
        }
        return $this->identity;
    }

    public function getKey()
    {
        return $this->key;
    }


    public function setKey($key)
    {
        return $this->key;
    }

    public function update(\G4\DataMapper\Common\MappingInterface $map, \G4\DataMapper\Common\IdentityInterface $identity)
    {
        $this->map      = $map;
        $this->identity = $identity;
        $this->action   = self::ACTION_UPDATE;
        return $this;
    }

    public function upsert(\G4\DataMapper\Common\MappingInterface $map)
    {
        $this->map      = $map;
        $this->action   = self::ACTION_UPSERT;
        return $this;
    }

    public function delete(\G4\DataMapper\Common\IdentityInterface $identity)
    {
        $this->identity = $identity;
        $this->action   = self::ACTION_DELETE;
        return $this;
    }

    public function insert(\G4\DataMapper\Common\MappingInterface $map)
    {
        $this->map = $map;
        $this->action = self::ACTION_INSERT;
        return $this;
    }

    public function isUpsert()
    {
        return $this->getAction() === self::ACTION_UPSERT;
    }

    public function isInsert()
    {
        return $this->getAction() === self::ACTION_INSERT;
    }

    public function isUpdate()
    {
        return $this->getAction() === self::ACTION_UPDATE;
    }

    public function isDelete()
    {
        return $this->getAction() === self::ACTION_DELETE;
    }

    private function getAction()
    {
        if($this->action === self::ACTION_INSERT
            || $this->action === self::ACTION_DELETE
            || $this->action === self::ACTION_UPDATE
            || $this->action === self::ACTION_UPSERT){

            return $this->action;
        }
        throw new MissingActionException();
    }

}