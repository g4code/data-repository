<?php

namespace G4\DataRepository;

use G4\DataRepository\Exception\MissingActionException;
use G4\DataRepository\Exception\MissingCustomCommandException;
use G4\DataRepository\Exception\MissingIdentityException;
use G4\DataRepository\Exception\MissingIdentityMapKeyException;
use G4\DataRepository\Exception\MissingMapperException;
use G4\DataRepository\Exception\MissingRussianDollKeyException;

class RepositoryCommand
{
    const ACTION_UPSERT = 'upsert';
    const ACTION_INSERT = 'insert';
    const ACTION_DELETE = 'delete';
    const ACTION_UPDATE = 'update';
    const CUSTOM_COMMAND= 'custom_command';
    const DELIMITER     = '|';
    /**
     * @var \G4\DataMapper\Common\MappingInterface
     */
    private $map;
    private $russianDollKey;
    private $identityMapKey;
    private $customCommand;

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
        if (!$this->map instanceof \G4\DataMapper\Common\MappingInterface) {
            throw new MissingMapperException();
        }
        return $this->map;
    }

    /**
     * @return  \G4\DataMapper\Common\IdentityInterface
     */
    public function getIdentity()
    {
        if (!$this->identity instanceof \G4\DataMapper\Common\IdentityInterface) {
            throw new MissingIdentityException();
        }
        return $this->identity;
    }

    public function getRussianDollKey()
    {
        if (!$this->russianDollKey instanceof \G4\RussianDoll\Key) {
            throw new MissingRussianDollKeyException();
        }
        return $this->russianDollKey;
    }

    public function getIdentityMapKey()
    {
        if (empty($this->identityMapKey)) {
            throw new MissingIdentityMapKeyException();
        }
        return $this->identityMapKey;
    }

    public function setRussianDollKey(\G4\RussianDoll\Key $russianDollKey)
    {
        $this->russianDollKey = $russianDollKey;
        return $this;
    }

    public function setIdentityMapKey(...$identityMapKey)
    {
        $this->identityMapKey = join(self::DELIMITER, $identityMapKey);
        return $this;
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

    public function isCustomCommand()
    {
        return $this->getAction() === self::CUSTOM_COMMAND;
    }

    private function getAction()
    {
        if ($this->action === self::ACTION_INSERT
            || $this->action === self::ACTION_DELETE
            || $this->action === self::ACTION_UPDATE
            || $this->action === self::ACTION_UPSERT
            || $this->action === self::CUSTOM_COMMAND
        ) {
            return $this->action;
        }
        throw new MissingActionException();
    }

    public function hasCustomCommand()
    {
        return !empty($this->customCommand);
    }

    public function getCustomCommand()
    {
        if (!$this->hasCustomCommand()) {
            throw new MissingCustomCommandException();
        }
        return $this->customCommand;
    }

    public function setCustomCommand($customCommand)
    {
        $this->action = self::CUSTOM_COMMAND;
        $this->customCommand = $customCommand;
        return $this;
    }
}
