# Changelog

## 6.0.0 alpha 1 (2026-XX-XX)

### Breaking Compatibility Changes

- Changed `Phalcon\Mvc\Dispatcher::getControllerName()` and `Phalcon\Mvc\Dispatcher::getPreviousControllerName()` to return the uncamelized (snake_case lowercase) controller name, restoring the behavior from Phalcon 4 and ensuring consistent output regardless of the casing used in route definitions [#CP-15996](https://github.com/phalcon/cphalcon/issues/15996)
-

### Fixed

- Fixed `Phalcon\Mvc\Model::__get()` to cache and return an already-loaded single-model (non-reusable `hasOne`/`belongsTo`) from `$this->related` on subsequent accesses, preventing modifications to the related object from being discarded; resultsets (`hasMany`) and reusable relations are unaffected and continue to fetch fresh data [#15554](https://github.com/phalcon/cphalcon/issues/15554)
- Fixed `Phalcon\Mvc\Model\Query\Builder::getPhql()` to use a named bind parameter (`:APK0:`) instead of embedding the raw primary-key value in the PHQL string when `findFirst()` is called with a numeric or numeric-string argument; this prevents unbounded growth of the internal PHQL AST cache (`Query::$internalPhqlCache`) in long-running CLI processes [#14656](https://github.com/phalcon/cphalcon/issues/14656)
- Fixed `Phalcon\Filter\Validation\AbstractValidator::allowEmpty()` to support a value-list array (e.g. `[null, '']`) in addition to the per-field map syntax, using strict `===` comparison so that `'0'` is never silently treated as empty [#15491](https://github.com/phalcon/cphalcon/issues/15491)
- Fixed `Phalcon\Filter\Validation\Validator\Alpha::validate()` to return `false` when `allowEmpty` is explicitly set to `false` and the submitted value is `null` or an empty string, preventing the regex from silently passing empty input [#16200](https://github.com/phalcon/cphalcon/issues/16200)
- Fixed `Phalcon\Mvc\Model::cloneResultMap()` to call model setter methods (e.g. `setName()`) during ORM hydration when `orm.disable_assign_setters` is `false`, making hydration behaviour consistent with `assign()`; setters in `localMethods` (Phalcon internals) are excluded [#14810](https://github.com/phalcon/cphalcon/issues/14810)
- Fixed `Phalcon\Mvc\Model::toArray()` to catch `Error` thrown by a getter that accesses an uninitialized typed PHP property (can occur when `cloneResultMap()` skips a null value for a NOT NULL column, e.g. via a LEFT JOIN), returning `null` instead of propagating the error; also changed `property_exists()` to `isset()` in the non-getter path so uninitialized typed properties are handled safely there too [#15711](https://github.com/phalcon/cphalcon/issues/15711)
- Fixed `Phalcon\Mvc\Model::unserialize()` to catch `TypeError` when assigning a serialised `null` back to a typed non-nullable PHP property, preventing a crash on the second request when the model is loaded from a cache like APCu [#15711](https://github.com/phalcon/cphalcon/issues/15711) 