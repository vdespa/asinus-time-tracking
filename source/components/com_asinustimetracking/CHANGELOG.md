# Asinus Time-Tracking Changelog

### 1.1.18
- Fixed generate monthly report does not work in FE after updating to Joomla 2.5.28 Issue: #11

### 1.1.17
- Fixed code quality issues in relation to issue #10.

### 1.1.16
- This release addresses issues with the previous release. Fixed issue introduced by new configuration parameter.

### 1.1.15
- Added the possibility of overriding Excel templates. The process is documented on github.
- In montly report template 2, changed column K format to industrial minutes.
- Added submenu in BE.

### 1.1.14
- Added configuration option for the number of days the editing is available
- Hardcoded pause interval between 12:00 and 12:45 (later to be configurable as well).

### 1.1.13
- Fixed issue with "Configuration" - "Site" - "List limit" setting
- improved AsinusTimeTrackingModelTimeTrack::getListQuery()

### 1.1.0 to 1.1.12
- Monthly report in Excel
- Various bug fixes related to the report
- Various bug fixes in the system

### 1.0.0
- Forked project com_timetrack v. 1.5.4 for Joomla 1.7
- Created package for installation
- Data importer from com_timetrack after installing
- Fixed most of the code quality related issues (notices, warnings)