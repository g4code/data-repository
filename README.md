Data Repository
==========
```php
<?php 

/***************** mysql, russian doll, memory select *****************/

$dataRepository = (new DataRepositoryFactory(
    Builder::create()->adapter(ew \G4\DataMapper\Engine\MySQL\MySQLAdapter(
        new \G4\DataMapper\Engine\MySQL\MySQLClientFactory($configData)
    )),
    new RussianDoll(DI::mcacheInstance()),
    new \G4\IdentityMap\IdentityMap()
))->create();

$identity = new Identity();
$identity
    ->field('user_id')
    ->equal(12345);
    
$response =  $this->repository
    ->setDatasetName('users')
    ->setIdentity($identity)
    ->setIdentityMapKey('users', 12345)
    ->setRussianDollKey(new Key('users', 12345))
    ->select();    

/***************** mysql, russian doll, memory update, insert, upsert, delete *****************/

$dataRepository = (new DataRepositoryFactory(
    Builder::create()->adapter(ew \G4\DataMapper\Engine\MySQL\MySQLAdapter(
        new \G4\DataMapper\Engine\MySQL\MySQLClientFactory($configData)
    )),
    new RussianDoll(DI::mcacheInstance()),
    new \G4\IdentityMap\IdentityMap()
))->create();

$userEntity = new UserEntity();

$identity = new Identity();
$identity
    ->field('user_id')
    ->equal($profileEntity->getId());
    
//Update
$this->repository
    ->setDatasetName('users')
    ->setIdentity($identity)
    ->setIdentityMapKey('users', $userEntity->getId())
    ->setRussianDollKey(new Key('users', $userEntity->getId()))
    ->setMapping(new UserMap($userEntity))) // has to implement MappingInterface
    ->update();   
    
//Upsert
$this->repository
    ->setDatasetName('users')
    ->setIdentity($identity)
    ->setIdentityMapKey('users', $userEntity->getId())
    ->setRussianDollKey(new Key('users', $userEntity->getId()))
    ->setMapping(new UserMap($userEntity))) // has to implement MappingInterface
    ->upsert();   
    
//Insert    
$this->repository
    ->setDatasetName('users')
    ->setIdentityMapKey('users', $userEntity->getId())
    ->setRussianDollKey(new Key('users', $userEntity->getId()))
    ->setMapping(new UserMap($userEntity))) // has to implement MappingInterface
    ->insert(); 
    
//Delete
$this->repository
    ->setDatasetName('users')
    ->setIdentity($identity)
    ->setIdentityMapKey('users', $userEntity->getId())
    ->setRussianDollKey(new Key('users', $userEntity->getId()))
    ->delete();      

/***************** Simple mysql select *****************/

$dataRepository = (new DataRepositoryFactory(
    Builder::create()->adapter(ew \G4\DataMapper\Engine\MySQL\MySQLAdapter(
        new \G4\DataMapper\Engine\MySQL\MySQLClientFactory($configData)
    ))
))->create();

$identity = new Identity();
$identity
    ->field('user_id')
    ->equal(12345);
    
$response =  $this->repository
    ->setDatasetName('users')
    ->setIdentity($identity)
    ->select();        
    
