data-repository
==========

> data-repository - repository library for [php](http://php.net). 
> * database storage with [data-mapper](https://github.com/g4code/data-mapper)
> * caching with [russian-doll](https://github.com/g4code/russian-doll)
> * in-memory data store with [identity-map](https://github.com/g4code/identity-map)

## Install

Install through  [composer](https://getcomposer.org/) package manager.
Find it on [packagist](https://packagist.org/packages/g4/data-repository).

```sh
composer require g4/data-repository
```
Dependencies:
* [g4/data-mapper](https://packagist.org/packages/g4/data-mapper)
* [g4/russian-doll](https://packagist.org/packages/g4/russian-doll)
* [g4/identity-map](https://packagist.org/packages/g4/identity-map)

## Usage

Check data-mapper docs for details - [data-mapper](https://github.com/g4code/data-mapper/blob/master/README.md)

Check russian-doll docs for details - [russian-doll](https://github.com/g4code/russian-doll/blob/master/README.md)

Check identity-map docs for details - [identity-map](https://github.com/g4code/identity-map/blob/master/README.md)

```php

use G4\DataMapper\Builder;
use G4\DataMapper\Common\Identity;
use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Engine\MySQL\MySQLAdapter;
use G4\DataMapper\Engine\MySQL\MySQLClientFactory;
use G4\DataRepository\DataRepositoryFactory;
use G4\IdentityMap\IdentityMap;
use G4\Mcache\McacheFactory;
use G4\RussianDoll\Key;
use G4\RussianDoll\RussianDoll;

// Create instance

$dataRepository = (new DataRepositoryFactory(
            Builder::create()->adapter(new MySQLAdapter(new MySQLClientFactory([]))),
            new RussianDoll(McacheFactory::createInstance('__driver_name__', [], '__prefix__')),
            new IdentityMap()
        ))->create();


// Read flow with: data-mapper's mysql engine, russian-doll and identity-map 

$identity = new Identity();
$identity
    ->field('__field_name__')
    ->equal('__field_value__');

$response = $dataRepository
    ->setDatasetName('__table_name__')
    ->setIdentity($identity)
    ->setIdentityMapKey('__table_name__', '__field_name__', '__field_value__')
    ->setRussianDollKey(new Key('__table_name__', '__field_name__', '__field_value__'))
    ->select();

var_dump($response);


// Write flow (insert, update, upsert, delete) with: data-mapper's mysql engine, russian-doll, and identity-map 

$identity = new Identity();
$identity
    ->field('__field_name__')
    ->equal('__field_value__');

$this->repository
    ->setDatasetName('__table_name__')
    ->setMapping(new Mapp())) // must implement mapping
    ->insert();

$dataRepository
    ->setDatasetName('__table_name__')
    ->setIdentity($identity)
    ->setIdentityMapKey('__table_name__', '__field_name__', '__field_value__')
    ->setRussianDollKey(new Key('__table_name__', '__field_name__', '__field_value__'))
    ->setMapping(new Mapp())) // must implement mapping
    ->update();

$this->repository
    ->setDatasetName('__table_name__')
    ->setIdentity($identity)
    ->setIdentityMapKey('__table_name__', '__field_name__', '__field_value__')
    ->setRussianDollKey(new Key('__table_name__', '__field_name__', '__field_value__'))
    ->setMapping(new Mapp())) // must implement mapping
    ->upsert();

$this->repository
    ->setDatasetName('__table_name__')
    ->setIdentity($identity)
    ->setIdentityMapKey('__table_name__', '__field_name__', '__field_value__')
    ->setRussianDollKey(new Key('__table_name__', '__field_name__', '__field_value__'))
    ->delete();
    
 $this->repository
    ->setDatasetName('__table_name__')
    ->setIdentityMapKey('__table_name__', '__field_name__', '__field_value__')
    ->setRussianDollKey(new Key('__table_name__', '__field_name__', '__field_value__'))
    ->command('DELETE FROM __table_name__ WHERE __field_name__ = __field_value__');
 
```

## Development

### Install dependencies

    $ composer install

### Run tests

    $ composer unit-test

## License

(The MIT License)
see LICENSE file for details...       
    
