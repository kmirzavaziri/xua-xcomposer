<?php

class XComposerAutoloaderInit
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/../../composer/ClassLoader.php';
        }
        if ('Xua\XComposer\Autoload\XClassLoader' === $class) {
            require __DIR__ . '/XClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('XComposerAutoloaderInit', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Xua\XComposer\Autoload\XClassLoader(\dirname(\dirname(__FILE__)));
        spl_autoload_unregister(array('XComposerAutoloaderInit', 'loadClassLoader'));

        $useStaticLoader = PHP_VERSION_ID >= 50600 && !defined('HHVM_VERSION') && (!function_exists('zend_loader_file_encoded') || !zend_loader_file_encoded());
        if ($useStaticLoader) {
            require __DIR__ . '/../../composer/autoload_static.php';

            call_user_func(\Composer\Autoload\ComposerStaticInitSuffixForXuaXComposer::getInitializer($loader));
        } else {
            $map = require __DIR__ . '/../../composer/autoload_namespaces.php';
            foreach ($map as $namespace => $path) {
                $loader->set($namespace, $path);
            }

            $map = require __DIR__ . '/../../composer/autoload_psr4.php';
            foreach ($map as $namespace => $path) {
                $loader->setPsr4($namespace, $path);
            }

            $classMap = require __DIR__ . '/../../composer/autoload_classmap.php';
            if ($classMap) {
                $loader->addClassMap($classMap);
            }
        }

        $loader->register(true);

        if ($useStaticLoader) {
            $includeFiles = Composer\Autoload\ComposerStaticInitSuffixForXuaXComposer::$files;
        } else {
            $includeFiles = require __DIR__ . '/../../composer/autoload_files.php';
        }
        foreach ($includeFiles as $fileIdentifier => $file) {
            xComposerRequire($fileIdentifier, $file);
        }

        return $loader;
    }
}

function xComposerRequire($fileIdentifier, $file)
{
    if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
        require $file;

        $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;
    }
}

return XComposerAutoloaderInit::getLoader();
