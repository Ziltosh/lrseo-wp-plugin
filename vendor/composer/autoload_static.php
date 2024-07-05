<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdbf78327257a3845bf44148d016ace1f
{
    public static $files = array (
        '7b11c4dc42b3b3023073cb14e519683c' => __DIR__ . '/..' . '/ralouphie/getallheaders/src/getallheaders.php',
        '6e3fae29631ef280660b3cdad06f25a8' => __DIR__ . '/..' . '/symfony/deprecation-contracts/function.php',
        '37a3dc5111fe8f707ab4c132ef1dbc62' => __DIR__ . '/..' . '/guzzlehttp/guzzle/src/functions_include.php',
    );

    public static $prefixLengthsPsr4 = array (
        'V' => 
        array (
            'ViteHelpers\\' => 12,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Http\\Client\\' => 16,
        ),
        'O' => 
        array (
            'Orhanerday\\OpenAi\\' => 18,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Psr7\\' => 16,
            'GuzzleHttp\\Promise\\' => 19,
            'GuzzleHttp\\' => 11,
        ),
        'F' => 
        array (
            'Front\\' => 6,
        ),
        'C' => 
        array (
            'Claude\\Claude3Api\\' => 18,
        ),
        'A' => 
        array (
            'Admin\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ViteHelpers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/ViteHelpers',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
            1 => __DIR__ . '/..' . '/psr/http-factory/src',
        ),
        'Psr\\Http\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-client/src',
        ),
        'Orhanerday\\OpenAi\\' => 
        array (
            0 => __DIR__ . '/..' . '/orhanerday/open-ai/src',
        ),
        'GuzzleHttp\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
        'GuzzleHttp\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/promises/src',
        ),
        'GuzzleHttp\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
        ),
        'Front\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Front',
        ),
        'Claude\\Claude3Api\\' => 
        array (
            0 => __DIR__ . '/..' . '/claude-php/claude-3-api/src',
        ),
        'Admin\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Admin',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdbf78327257a3845bf44148d016ace1f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdbf78327257a3845bf44148d016ace1f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitdbf78327257a3845bf44148d016ace1f::$classMap;

        }, null, ClassLoader::class);
    }
}
