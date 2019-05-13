<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit73cdb86a45a622add1763e70c34320ef
{
    public static $files = array (
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'Z' => 
        array (
            'Zend\\Xml2Json\\' => 14,
            'Zend\\Json\\' => 10,
            'ZendXml\\' => 8,
        ),
        'T' => 
        array (
            'Tests\\' => 6,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Ctype\\' => 23,
            'Scriptotek\\Marc\\' => 16,
        ),
        'P' => 
        array (
            'PHPOnCouch\\Exceptions\\' => 22,
            'PHPOnCouch\\Adapter\\' => 19,
            'PHPOnCouch\\' => 11,
        ),
        'D' => 
        array (
            'Dotenv\\' => 7,
        ),
        'C' => 
        array (
            'CK\\MARCspec\\Exception\\' => 22,
            'CK\\MARCspec\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Zend\\Xml2Json\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-xml2json/src',
        ),
        'Zend\\Json\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-json/src',
        ),
        'ZendXml\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zendxml/src',
        ),
        'Tests\\' => 
        array (
            0 => __DIR__ . '/..' . '/scriptotek/marc/tests',
        ),
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'Scriptotek\\Marc\\' => 
        array (
            0 => __DIR__ . '/..' . '/scriptotek/marc/src',
        ),
        'PHPOnCouch\\Exceptions\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-on-couch/php-on-couch/src/Exceptions',
        ),
        'PHPOnCouch\\Adapter\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-on-couch/php-on-couch/src/Adapter',
        ),
        'PHPOnCouch\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-on-couch/php-on-couch/src',
        ),
        'Dotenv\\' => 
        array (
            0 => __DIR__ . '/..' . '/vlucas/phpdotenv/src',
        ),
        'CK\\MARCspec\\Exception\\' => 
        array (
            0 => __DIR__ . '/..' . '/ck/php-marcspec/src/Exception',
        ),
        'CK\\MARCspec\\' => 
        array (
            0 => __DIR__ . '/..' . '/ck/php-marcspec/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'PEAR' => 
            array (
                0 => __DIR__ . '/..' . '/pear/pear_exception',
            ),
        ),
        'F' => 
        array (
            'File' => 
            array (
                0 => __DIR__ . '/..' . '/pear/file_marc',
            ),
        ),
    );

    public static $classMap = array (
        'File_MARC_Reference' => __DIR__ . '/..' . '/ck/file_marc_reference/src/File_MARC_Reference.php',
        'File_MARC_Reference_Cache' => __DIR__ . '/..' . '/ck/file_marc_reference/src/File_MARC_Reference_Cache.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit73cdb86a45a622add1763e70c34320ef::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit73cdb86a45a622add1763e70c34320ef::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit73cdb86a45a622add1763e70c34320ef::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit73cdb86a45a622add1763e70c34320ef::$classMap;

        }, null, ClassLoader::class);
    }
}
