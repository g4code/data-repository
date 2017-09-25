<?php

namespace G4\DataRepository;

use G4\DataRepository\Exception\MissingIdentityException;
use G4\DataRepository\Exception\MissingIdentityMapKeyException;
use G4\DataRepository\Exception\MissingRussianDollKeyException;

class RepositoryQuery
{
    /*
     * @var \G4\DataMapper\Common\IdentityInterface
     */
    private $identity;

    private $identityMapKey;

    /*
     * @var \G4\RussianDoll\Key
     */
    private $russianDollKey;

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