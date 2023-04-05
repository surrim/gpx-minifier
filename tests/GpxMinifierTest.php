<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Surrim\GpxMinifier\GpxMinifier;

class GpxMinifierTest extends TestCase {
  public function testMinifyGpxFile(): void {
    $filename = __DIR__ . '/sample.gpx';
    $minified = GpxMinifier::minifyGpxFile($filename)->asXML();

    $minifiedSize = strlen($minified);
    $originalSize = filesize($filename);
    echo "Reduced $originalSize to $minifiedSize bytes";
    self::assertGreaterThan($minifiedSize, $originalSize);
  }
}
