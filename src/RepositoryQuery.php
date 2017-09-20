<?php

namespace G4\DataRepository;

class RepositoryQuery implements QueryInterface
{
    const DATA_TYPE_ALL = 'all';
    const DATA_TYPE_ONE = 'one';
    /*
     * @var \G4\DataMapper\Common\IdentityInterface
     */
    private $identity;

    private $dataType;

    /**
     * @return \G4\DataMapper\Common\IdentityInterface
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    public function setIdentity(\G4\DataMapper\Common\IdentityInterface $identity)
    {
        $this->identity = $identity;
        return $this;
    }

    public function getOne()
    {
        $this->dataType = self::DATA_TYPE_ONE;
        return $this;
    }

    public function getAll()
    {
        $this->dataType = self::DATA_TYPE_ALL;
        return $this;
    }

    public function isDataTypeOne()
    {
        return $this->dataType === self::DATA_TYPE_ONE;
    }
}