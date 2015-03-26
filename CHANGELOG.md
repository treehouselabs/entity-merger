## 2.0.0 - 2015-03-26

### Changed

* BC BREAK: Constructor signature changed: `MetadataFactory` was added
* BC BREAK: `doMerge` method changed from public to protected
* BC BREAK: `merge` method signature changed: `$groups` is changed to `$exclusionStrategy`

### Added

* Possibility to merge null values
* Fields exclusion strategy
* A bit more tests

### Fixed

* exclusion strategy when using groups (or now passing an exclusionStrategy yourself) 
