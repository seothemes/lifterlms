<?php

namespace Composer;

use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-trunk',
    'version' => 'dev-trunk',
    'aliases' => 
    array (
    ),
    'reference' => '30e6c13da6bcd66bf29fd3e1854ff3bad606fb37',
    'name' => 'gocodebox/lifterlms',
  ),
  'versions' => 
  array (
    'composer/installers' => 
    array (
      'pretty_version' => 'v1.9.0',
      'version' => '1.9.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b93bcf0fa1fccb0b7d176b0967d969691cd74cca',
    ),
    'deliciousbrains/wp-background-processing' => 
    array (
      'pretty_version' => '1.0.2',
      'version' => '1.0.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '2cbee1abd1b49e1133cd8f611df4d4fc5a8b9800',
    ),
    'gocodebox/lifterlms' => 
    array (
      'pretty_version' => 'dev-trunk',
      'version' => 'dev-trunk',
      'aliases' => 
      array (
      ),
      'reference' => '30e6c13da6bcd66bf29fd3e1854ff3bad606fb37',
    ),
    'lifterlms/lifterlms-blocks' => 
    array (
      'pretty_version' => '2.0.0-rc.1',
      'version' => '2.0.0.0-RC1',
      'aliases' => 
      array (
      ),
      'reference' => '99ed242dc26cf4c3c0852a3066b6689451e743f5',
    ),
    'lifterlms/lifterlms-helper' => 
    array (
      'pretty_version' => '3.3.0',
      'version' => '3.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'fb54a6464bd373f83783d5dc50004b818c005c56',
    ),
    'lifterlms/lifterlms-rest' => 
    array (
      'pretty_version' => '1.0.0-beta.19',
      'version' => '1.0.0.0-beta19',
      'aliases' => 
      array (
      ),
      'reference' => 'aa8599da427182c42303fb3bac5da4462e26e65e',
    ),
    'roundcube/plugin-installer' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'shama/baton' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'woocommerce/action-scheduler' => 
    array (
      'pretty_version' => '3.2.0',
      'version' => '3.2.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '12c91fd20af0cdfb6107b8db9df78d133f512b04',
    ),
  ),
);







public static function getInstalledPackages()
{
return array_keys(self::$installed['versions']);
}









public static function isInstalled($packageName)
{
return isset(self::$installed['versions'][$packageName]);
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

$ranges = array();
if (isset(self::$installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = self::$installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}





public static function getVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['version'])) {
return null;
}

return self::$installed['versions'][$packageName]['version'];
}





public static function getPrettyVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return self::$installed['versions'][$packageName]['pretty_version'];
}





public static function getReference($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['reference'])) {
return null;
}

return self::$installed['versions'][$packageName]['reference'];
}





public static function getRootPackage()
{
return self::$installed['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
}
}
