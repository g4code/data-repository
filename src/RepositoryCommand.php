<?php

namespace G4\DataRepository;

use G4\DataRepository\Exception\MissingActionException;
use G4\DataRepository\Exception\MissingCustomCommandException;
use G4\DataRepository\Exception\MissingIdentityException;
use G4\DataRepository\Exception\MissingIdentityMapKeyException;
use G4\DataRepository\Exception\MissingMapperException;
use G4\DataRepository\Exception\MissingRussianDollKeyException;
use G4\DataMapper\Common\IdentityInterface;
use G4\DataMapper\Common\MappingInterface;
use G4\RussianDoll\Key;

class RepositoryCommand
{
    final public const ACTION_UPSERT = 'upsert';
    final public const ACTION_INSERT = 'insert';
    final public const ACTION_DELETE = 'delete';
    final public const ACTION_UPDATE = 'update';
    final public const CUSTOM_COMMAND= 'custom_command';
    final public const DELIMITER     = '|';

    private ?MapperCollection $map = null;
    private ?Key $russianDollKey = null;
    private ?string $identityMapKey = null;
    private $customCommand;

    private ?IdentityInterface $identity = null;

    private ?string $action = null;

    /**
     * @throws MissingMapperException
     */
    public function getMap(): ?MapperCollection
    {
        if (!$this->map instanceof MapperCollection) {
            throw new MissingMapperException();
        }
        return $this->map;
    }

    /**
     * @throws MissingIdentityException
     */
    public function getIdentity(): ?IdentityInterface
    {
        if (!$this->identity instanceof IdentityInterface) {
            throw new MissingIdentityException();
        }
        return $this->identity;
    }

    public function getRussianDollKey(): ?Key
    {
        if (!$this->russianDollKey instanceof Key) {
            throw new MissingRussianDollKeyException();
        }
        return $this->russianDollKey;
    }

    public function getIdentityMapKey(): ?string
    {
        if (empty($this->identityMapKey)) {
            throw new MissingIdentityMapKeyException();
        }
        return $this->identityMapKey;
    }

    public function setRussianDollKey(Key $russianDollKey): self
    {
        $this->russianDollKey = $russianDollKey;
        return $this;
    }

    public function setIdentityMapKey(...$identityMapKey): self
    {
        $this->identityMapKey = join(self::DELIMITER, $identityMapKey);
        return $this;
    }

    public function update(MapperCollection $map, IdentityInterface $identity): self
    {
        $this->map      = $map;
        $this->identity = $identity;
        $this->action   = self::ACTION_UPDATE;
        return $this;
    }

    public function upsert(MapperCollection $map): self
    {
        $this->map      = $map;
        $this->action   = self::ACTION_UPSERT;
        return $this;
    }

    public function delete(IdentityInterface $identity): self
    {
        $this->identity = $identity;
        $this->action   = self::ACTION_DELETE;
        return $this;
    }

    public function insert(MapperCollection $maps): self
    {
        $this->map = $maps;
        $this->action = self::ACTION_INSERT;
        return $this;
    }

    public function isUpsert(): bool
    {
        return $this->getAction() === self::ACTION_UPSERT;
    }

    public function isInsert(): bool
    {
        return $this->getAction() === self::ACTION_INSERT;
    }

    public function isUpdate(): bool
    {
        return $this->getAction() === self::ACTION_UPDATE;
    }

    public function isDelete(): bool
    {
        return $this->getAction() === self::ACTION_DELETE;
    }

    public function isCustomCommand(): bool
    {
        return $this->getAction() === self::CUSTOM_COMMAND;
    }

    private function getAction(): ?string
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

    public function hasCustomCommand(): bool
    {
        return !empty($this->customCommand);
    }

    public function getCustomCommand(): mixed
    {
        if (!$this->hasCustomCommand()) {
            throw new MissingCustomCommandException();
        }
        return $this->customCommand;
    }

    public function setCustomCommand($customCommand): self
    {
        $this->action = self::CUSTOM_COMMAND;
        $this->customCommand = $customCommand;
        return $this;
    }
}
