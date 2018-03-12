# Change Log for OXID eShop doctrine migration integration

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/ ).
and this project adheres to [Semantic Versioning](http://semver.org/ ).

## [2.1.2] - Unreleased 

### Added
### Changed 
### Deprecated
### Removed
### Fixed
### Security


## [2.1.1] - 2018-03-12

### Changed

- Pdo_mysql is used instead of mysqli as a database driver. 

### Fixed

- No more illegal mix of collation errors if collation_server was configured to something else than utf8_general_ci.

[2.1.2]: https://github.com/OXID-eSales/oxideshop-doctrine-migration-wrapper/compare/v2.1.1...v2.1.2
[2.1.1]: https://github.com/OXID-eSales/oxideshop-doctrine-migration-wrapper/compare/v2.1.0...v2.1.1