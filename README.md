Entity Merger
=============

**Entity Merger** is a library that can merge two entities.

[![Build Status](https://travis-ci.org/treehouselabs/entity-merger.svg?branch=master)](https://travis-ci.org/treehouselabs/entity-merger)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/treehouselabs/entity-merger/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/treehouselabs/entity-merger/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/treehouselabs/entity-merger/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/treehouselabs/entity-merger/?branch=master)

Usage
=====

Define as a service:

```yaml
tree_house.entity_merger:
    class: TreeHouse\EntityMerger\EntityMerger
    arguments:
      - @jms_serializer
      - @doctrine
      - @jms_serializer.metadata_factory
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

To update null values, you can hint the merger to do this:

```php
$merger = $container->get('tree_house.entity_merger');

$original = new Entity();
$original->setTitle('A');

$update = new Entity();
$update->setTitle(null);
$update->setAuthor('TreeHouse');

$original = $merger->merge($original, $update, null, true);

var_dump($original->getTitle()) // output: NULL
var_dump($original->getAuthor()); // output: "TreeHouse"
```

It is also possible to exclude fields by giving an array with property names which must be included:

```php
$merger = $container->get('tree_house.entity_merger');

$original = new Entity();
$original->setTitle('A');

$update = new Entity();
$update->setTitle('B');
$update->setAuthor('TreeHouse');

// include the given fields, make sure to camelcase the fieldnames when needed
$exclusionStrategy = new \TreeHouse\EntityMerger\Serializer\Exclusion\FieldsExclusionStrategy([
    'title'
]);

$original = $merger->merge($original, $update, $exclusionStrategy);

var_dump($original->getTitle()) // output: "B"
var_dump($original->getAuthor()); // output: NULL
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
