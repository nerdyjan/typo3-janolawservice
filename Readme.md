# TYPO3 janloaw Extension

For more information please visit https://www.janolaw.de/agb-service/einbindung-typo3.html

# Installation

## 1.Installation using Composer

The recommended way to install the extension is by using Composer. In your Composer based TYPO3 project root, just do

composer require typo3-ter/janolawservice

or

composer require janolaw/janolawservice

## 2. Installation as extension from TYPO3 Extension Repository (TER)

Download and install the extension with the extension manager module.

## 3. Update from Version 4.x to 5.x

Version 5 supports TYPO3 13.4 LTS and TYPO3 14 (PHP 8.2 or newer). TYPO3 12 is no longer supported.
After the update, run "Analyze Database Structure" in the Install Tool / Maintenance module and flush all caches.
The TypoScript files have been renamed to `.typoscript`. They can be included as the static template "janolaw Service" or as the site set `janolaw/janolawservice`.

## 4. Update from Version 3.x to 4.x

If you update from an older Version (3.x) to Version 4 the upgrade wizard ‘Migrates janolawserive Plugin’ must be executed, the database structure may be updated and the caches must be deleted!

## 5. Update from Version 1.x to 2.x

If you update from an older Version (0.x oder 1.x) to Version 2 an update of the database structure is needed.
