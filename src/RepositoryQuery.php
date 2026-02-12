<?php

namespace G4\DataRepository;

use G4\DataMapper\Common\IdentityInterface;
use G4\DataRepository\Exception\InvalidQueryException;
use G4\DataRepository\Exception\MissingIdentityException;
use G4\DataRepository\Exception\MissingIdentityMapKeyException;
use G4\DataRepository\Exception\MissingRussianDollKeyException;
use G4\RussianDoll\Key;

class RepositoryQuery
{
    final public const DELIMITER = "|";

    private ?IdentityInterface $identity = null;

    private ?string $identityMapKey = null;

    private $customQuery;

    private ?Key $russianDollKey = null;

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

    /**
     * @throws InvalidQueryException
     */
    public function getCustomQuery(): mixed
    {
        if (!$this->hasCustomQuery()) {
            throw new InvalidQueryException();
        }
        return $this->customQuery;
    }

    public function setIdentity(IdentityInterface $identity): self
    {
        $this->identity = $identity;
        return $this;
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

    public function setCustomQuery($query): self
    {
        $this->customQuery = $query;
        return $this;
    }

    public function hasCustomQuery(): bool
    {
        return !empty($this->customQuery);
    }
}
