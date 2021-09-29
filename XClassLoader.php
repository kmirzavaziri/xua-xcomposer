<?php

namespace Xua\XComposer\Autoload;

use Composer\Autoload\ClassLoader;

class XClassLoader extends ClassLoader
{
    /**
     * Loads the given class or interface.
     *
     * @param  string    $class The name of the class
     * @return true|null True if loaded, null otherwise
     */
    public function loadClass($class)
    {
        if ($file = $this->findFile($class)) {
            includeFile($file);

            if (class_exists($class) and method_exists($class, 'init')) {
                $class::init();
            }

            return true;
        }

        return null;
    }
}

/**
 * Scope isolated include.
 *
 * Prevents access to $this/self from included files.
 *
 * @param  string $file
 * @return void
 * @private
 */
function includeFile($file)
{
    include $file;
}
