Entity Merger
=============

**Entity Merger** is a library that can merge two entities.

Usage
=====

Define as a service:

``yaml
tree_house.entity_merger:
    class: TreeHouse\EntityMerger\EntityMerger
    arguments:
      - @jms_serializer
      - @doctrine
```

Then use it as followed:

```php
$merger = $container->get('tree_house.entity_merger');

$original = new Entity();
$original->setTitle('A');

var_dump($original->getAuthor()); // output: NULL

$update = new Entity();
$update->setTitle('B');
$update->setAuthor('TreeHouse');

$original = $merger->merge($original, $update);

var_dump($original->getTitle()) // output: "B"
var_dump($original->getAuthor()); // output: "TreeHouse"
```

It is also possible to pass an array with fields and values:

```php
$merger = $container->get('tree_house.entity_merger');

$original = new Entity();
$original->setTitle('A');
$original->setAuthor('TreeHouse');

$update = ['title' => 'new title'];

$original = $merger->merge($original, $update);

var_dump($original->getTitle()); // output: "new title"
var_dump($original->getAuthor()); // output: "TreeHouse"
```
