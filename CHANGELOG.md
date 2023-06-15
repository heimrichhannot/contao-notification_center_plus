# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased] - 2023-02-01
- Changed: removed request dependency
- Fixed: display errors for ical addresses

## [1.6.3] - 2023-02-01
- Fixed: attachments issues ([#3])
- Fixed: missing request dependency ([#2])
- Fixed: usage of deprecated DOM class

## [1.6.2] - 2022-06-07
- Fixed: warning in php 8

## [1.6.1] - 2022-01-06
- Changed: fixed checks to reduce the possibility of invalid notification center tokens
- 
## [1.6.0] - 2021-11-22
- Changed: added checks to reduce the possibility of invalid notification center tokens

## [1.5.0] - 2021-08-31

- Added: support for php 8

## [1.4.4] - 2021-07-14

- fixed password module for contao >= 4.7

## [1.4.3] - 2021-04-26

- fixed markup issues for ics extension

## [1.4.2] - 2021-04-26

- fixed markup issues for ics extension

## [1.4.1] - 2021-04-26

- version bump

## [1.4.0] - 2021-04-26

- added new hook `modifyIcsFile`

## [1.3.3] - 2021-01-15

- fixed issues with cron command

## [1.3.2] - 2020-09-29

- fixed user tokens for uuid values

## [1.3.1] - 2020-09-28

- fixed ics bugs

## [1.3.0] - 2020-09-24

- added ics tokens for street, postal, city and country

## [1.2.0] - 2020-09-24

- added ics tokens to the default notification type arrays
- fixed readme
- added missing check for ics generation

## [1.1.0] - 2020-09-24

- added ics generation for attachments
- codestyle

## [1.0.39] - 2020-09-22

- fixed `contao.assets.files_context` service issue

## [1.0.38] - 2020-09-14

- allow status_messages 2.0

## [1.0.37] - 2020-09-09

- fixed constant error in contao 4.9 cron command
- removed idea folder

## [1.0.36] - 2020-02-24

### Added

- reset state in template

## [1.0.35] - 2018-10-05

### Fixed

- version compare in `ModulePasswordNotificationCenterPlus`

## [1.0.34] - 2018-09-07

### Fixed

- NotificationCenterPlus::addTokens() salutation_form

## [1.0.33] - 2018-09-05

### Fixed

- getToken by overwriting QueuedMessage model class

## [1.0.32] - 2018-01-29

### Fixed

- ModulePasswordNotificationCenterPlus for Contao 4.4.12+

## [1.0.31] - 2017-11-24

### Fixed

- inserttags for usage in contao commands

## [1.0.30] - 2017-11-24

### Fixed

- Contao 4 esi tag message error in backend queue info action, caused by cached inserttags (prevented caching)

## [1.0.29] - 2017-04-12

- created new tag

## [1.0.28] - 2017-04-06

### Changed

- added php7 support. fixed contao-core dependency

## [1.0.27] - 2017-02-30

### Fixed

- `##env_date##` was not replaced correct

### Added

- `##env_request_path##` token without query strings
- added context token reference within `README.md`

## [1.0.26] - 2017-02-14

### Added

- fixed haste_plus overridable properties

## [1.0.25] - 2017-02-13

### Added

- support for haste_plus overridable properties


[#3]: https://github.com/heimrichhannot/contao-notification_center_plus/issues/3
[#2]: https://github.com/heimrichhannot/contao-notification_center_plus/issues/2