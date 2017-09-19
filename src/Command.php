<?php

namespace G4\DataRepository;

use G4\DataRepository\Exception\MissingActionException;
use G4\DataRepository\Exception\MissingIdentityException;
use G4\DataRepository\Exception\MissingMapperException;
// TODO - change name to RepositoryCommand
class Command implements CommandInterface
{
    const ACTION_UPSERT = 'upsert';
    const ACTION_INSERT = 'insert';
    const ACTION_DELETE = 'delete';

    /**
     * @var \G4\DataMapper\Common\MappingInterface
     */
    private $map;

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
        // TODO: Implement getKey() method.
    }

    // TODO - set mapping interface
    public function setMap(\G4\DataMapper\Common\MappingInterface $map)
    {
        $this->map = $map;
        return $this;
    }

    /*
     * @return \G4\DataMapper\Common\IdentityInterface
     */
    public function setIdentity(\G4\DataMapper\Common\IdentityInterface $identity)
    {
        $this->identity = $identity;
        return $this;
    }

    public function upsert()
    {
        $this->action = self::ACTION_UPSERT;
        return $this;
    }

    public function delete()
    {
        $this->action = self::ACTION_DELETE;
        return $this;
    }

    public function isUpsert()
    {
        return $this->getAction() === self::ACTION_UPSERT;
    }

    public function isDelete()
    {
        return $this->getAction() === self::ACTION_DELETE;
    }

    private function getAction()
    {
        if($this->action === self::ACTION_INSERT
            || $this->action === self::ACTION_DELETE
            || $this->action === self::ACTION_UPSERT){

            return $this->action;
        }
        throw new MissingActionException();
    }

}