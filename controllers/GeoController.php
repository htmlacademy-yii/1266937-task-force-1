<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\Response;
use GuzzleHttp\Client;
use Yii;

class GeoController extends Controller
{
  public function actionIndex($address = null)
  {
    $api_key = Yii::$app->params['geocoderApiKey'];

    Yii::$app->response->format = Response::FORMAT_JSON;

    if (!$address) {
      return [];
    }

    $userCity = Yii::$app->user->identity->city->name ?? '';
    $fullAddress = "{$userCity}, {$address}";

    $client = new Client([
      'base_uri' => 'https://geocode-maps.yandex.ru/1.x/',
    ]);

    try {
      $response = $client->request('GET', '', [
        'query' => [
          'apikey' => $api_key,
          'geocode' => $fullAddress,
          'format' => 'json',
          'results' => 5
        ]
      ]);

      $data = json_decode($response->getBody()->getContents(), true);
      $result = [];

      $members = $data['response']['GeoObjectCollection']['featureMember'] ?? [];

      foreach ($members as $member) {
        $geoObject = $member['GeoObject'];
        $components = $geoObject['metaDataProperty']['GeocoderMetaData']['Address']['Components'] ?? [];

        $coords = explode(' ', $geoObject['Point']['pos']);

        $city = '';
        $others = [];

        foreach ($components as $component) {
          $kind = $component['kind'];
          $name = $component['name'];

          if ($kind === 'locality') {
            $city = $name;
          } elseif (\in_array($kind, ['district', 'area', 'street', 'house'])) {
            $others[] = $name;
          }
        }

        $addressParts = $city ? [$city, ...$others] : $others;

        $result[] = [
          'text' => implode(', ', array_unique($addressParts)),
          'long' => $coords[0] ?? null,
          'lat' => $coords[1] ?? null,
        ];
      }

      return $result;

    } catch (\Exception $e) {
      return ['error' => $e->getMessage()];
    }
  }
}