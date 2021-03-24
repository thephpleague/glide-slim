<?php

namespace Responses;

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Glide\Responses\SlimResponseFactory;
use PHPUnit\Framework\TestCase;

class SlimResponseFactoryTest extends TestCase
{
    public function testCreateInstance()
    {
        self::assertInstanceOf(
            'League\Glide\Responses\SlimResponseFactory',
            new SlimResponseFactory()
        );
    }

    public function testCreate()
    {
        $cache = new Filesystem(
            new LocalFilesystemAdapter(dirname(__DIR__))
        );

        $factory = new SlimResponseFactory();
        $response = $factory->create($cache, 'kayaks.jpg');

        self::assertInstanceOf('Slim\Http\Response', $response);
        self::assertEquals('image/jpeg', $response->getHeaderLine('Content-Type'));
        self::assertEquals('5175', $response->getHeaderLine('Content-Length'));
        self::assertStringContainsString(gmdate('D, d M Y H:i', strtotime('+1 years')), $response->getHeaderLine('Expires'));
        self::assertEquals('max-age=31536000, public', $response->getHeaderLine('Cache-Control'));
    }
}
