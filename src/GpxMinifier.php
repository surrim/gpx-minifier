<?php

namespace Surrim\GpxMinifier;

use Exception;
use SimpleXMLElement;

class GpxMinifier {
  public static function minifyGpxData(string $data): ?SimpleXMLElement {
    try {
      $gpx = new SimpleXMLElement($data);
    } catch (Exception) {
      return null;
    }
    return static::minifyGpx($gpx);
  }

  public static function minifyGpx(SimpleXMLElement $gpx): ?SimpleXMLElement {
    $tmpGpx = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes" ?><gpx version="1.1" creator="surrim.org" xmlns="http://www.topografix.com/GPX/1/1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd"></gpx>');
    foreach (($gpx->trk ?? []) as $trk) {
      $tmpTrk = $tmpGpx->addChild('trk');
      foreach (($trk->trkseg ?? []) as $trkseg) {
        $tmpTrkseg = $tmpTrk->addChild('trkseg');
        foreach (($trkseg->trkpt ?? []) as $trkpt) {
          $tmpTrkpt = $tmpTrkseg->addChild('trkpt');
          $tmpTrkpt->addAttribute('lat', $trkpt['lat']);
          $tmpTrkpt->addAttribute('lon', $trkpt['lon']);
          if (!empty($trkpt->ele)) {
            $tmpTrkpt->addChild('ele', $trkpt->ele);
          }
        }
      }
    }
    return $tmpGpx;
  }

  public static function minifyGpxFile(string $filename, bool $checkFilenameExtension = true): ?SimpleXMLElement {
    if ($checkFilenameExtension && !str_ends_with(strtolower($filename), '.gpx')) {
      return null;
    }
    try {
      $gpx = new SimpleXMLElement($filename, dataIsURL: true);
    } catch (Exception) {
      return null;
    }
    return self::minifyGpx($gpx);
  }
}