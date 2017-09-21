<?php

namespace G4\DataRepository;

use G4\DataRepository\Exception\MissingIdentityException;
use G4\DataRepository\Exception\MissingIdentityMapKeyException;
use G4\DataRepository\Exception\MissingRussianDollKeyException;

class RepositoryQuery implements QueryInterface
{
    const DATA_TYPE_ALL = 'all';
    const DATA_TYPE_ONE = 'one';

    /*
     * @var \G4\DataMapper\Common\IdentityInterface
     */
    private $identity;

    private $identityMapKey;

    /*
     * @var \G4\RussianDoll\Key
     */
    private $russianDollKey;

    private $dataType;


    public function getAll()
    {
        $this->dataType = self::DATA_TYPE_ALL;
        return $this;
    }

    /**
     * @return \G4\DataMapper\Common\IdentityInterface
     */
    public function getIdentity()
    {
        if(!$this->identity instanceof \G4\DataMapper\Common\IdentityInterface){
            throw new MissingIdentityException();
        }
        return $this->identity;
    }

    public function getOne()
    {
        $this->dataType = self::DATA_TYPE_ONE;
        return $this;
    }

    public function getRussianDollKey()
    {
        if(!$this->russianDollKey instanceof \G4\RussianDoll\Key){
            throw new MissingRussianDollKeyException();
        }
        return $this->russianDollKey;
    }

    public function getIdentityMapKey()
    {
        if(empty($this->identityMapKey)){
            throw new MissingIdentityMapKeyException();
        }
        return $this->identityMapKey;
    }

    public function isDataTypeOne()
    {
        return $this->dataType === self::DATA_TYPE_ONE;
    }

    public function setIdentity(\G4\DataMapper\Common\IdentityInterface $identity)
    {
        $this->identity = $identity;
        return $this;
    }

    public function setRussianDollKey(\G4\RussianDoll\Key $russianDollKey)
    {
        $this->russianDollKey = $russianDollKey;
        return $this;
    }

    public function setIdentityMapKey($identityMapKey)
    {
        $this->identityMapKey = $identityMapKey;
        return $this;
    }
}