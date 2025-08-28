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
