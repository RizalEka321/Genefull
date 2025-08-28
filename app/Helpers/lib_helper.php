<?php

function set_active($uri = '', $class = 'active')
{
  $request = service('request');
  $currentPath = trim($request->getUri()->getPath(), '/');

  $currentPath = preg_replace('#^index\.php/?#', '', $currentPath);

  if ($uri === '' || $uri === '/') {
    return ($currentPath === '' ? $class : '');
  }

  if (is_array($uri)) {
    foreach ($uri as $u) {
      if (stripos($currentPath, trim($u, '/')) === 0) {
        return $class;
      }
    }
    return '';
  }

  return (stripos($currentPath, trim($uri, '/')) === 0) ? $class : '';
}

function getRandomItem($array)
{
  return $array[array_rand($array)];
}

function pixabayApiRequest($api, $tipe = 'photo', $query = '', $perPage = 10, $page = 1)
{
  if ($tipe === 'video') {
    $url = "https://pixabay.com/api/videos/?key={$api}&q=" . urlencode($query)
      . "&per_page={$perPage}&page={$page}";
  } else {
    $url = "https://pixabay.com/api/?key={$api}&q=" . urlencode($query)
      . "&image_type=photo&per_page={$perPage}&page={$page}";
  }

  $res = file_get_contents($url);
  return json_decode($res, true);
}
