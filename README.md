Data Repository
==========
```php
<?php 

$dataRepository = (new DataRepositoryFactory(
    Builder::create()->adapter(DI::mysqlAdapter()),
    new RussianDoll(DI::mcacheInstance()),
    new \G4\IdentityMap\IdentityMap()
))->create();
