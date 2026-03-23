SQL builder
===========
Simple SQL builder based on PDO.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Examples
--------

### Select

```php
<?php

use Veejay\Sql\Sql;

$sql = new Sql('mysql:host=localhost;dbname=mydb', 'user', 'pass');
$rows = $sql->select()
    ->select('id', 'name')
    ->from('tbl')
    ->where('id>:id', ['id' => 1])
    ->limit(5)
    ->query();
```

### Insert

```php
<?php

use Veejay\Sql\Sql;

$sql = new Sql('mysql:host=localhost;dbname=mydb', 'user', 'pass');
$sql->insert()
    ->into('tbl')
    ->values(['name' => 'qwerty'])
    ->execute();
```

### Update

```php
<?php

use Veejay\Sql\Sql;

$sql = new Sql('mysql:host=localhost;dbname=mydb', 'user', 'pass');
$sql->update()
    ->table('tbl')
    ->set(['name' => 'qwerty'])
    ->where('id=:id', ['id' => 1])
    ->execute();
```

### Delete

```php
<?php

use Veejay\Sql\Sql;

$sql = new Sql('mysql:host=localhost;dbname=mydb', 'user', 'pass');
$sql->delete()
    ->from('tbl')
    ->where('id=:id', ['id' => 2])
    ->execute();
```

### Pure SQL requests

```php
<?php

use Veejay\Sql\Sql;

$sql = new Sql('mysql:host=localhost;dbname=mydb', 'user', 'pass');

$result = $sql->execute(
    'INSERT INTO tbl (id, name) VALUES (:id, :name)',
    ['id' => 1, 'name' => 'qwerty']
);

$rows = $sql->query(
    'SELECT * FROM tbl WHERE id=:id',
    ['id' => 1]
);
```

### Transaction

```php
<?php

use Veejay\Sql\Sql;

$sql = new Sql('mysql:host=localhost;dbname=mydb', 'user', 'pass');

$sql->beginTransaction();
$sql->rollback();
$sql->commit();
$sql->lastInsertId();
```

Requirements
------------
- PHP 8.0+
- Extension `ext-pdo`

Installation
------------
```
composer require veejay/sql
```
