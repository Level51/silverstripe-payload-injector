# Silverstripe Payload Injector

[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE.md)
[![Version](http://img.shields.io/packagist/v/level51/silverstripe-payload-injector.svg?style=flat)](https://packagist.org/packages/level51/silverstripe-payload-injector)

Utility for injecting JSON payload into the DOM at render time.

## Requirements

- Silverstripe 4.x

## Installation

- `composer require level51/silverstripe-payload-injector`
- Flush config (`flush=all`) to register the middleware

## Usage

If you do...

```php
public function index() {
  PayloadInjector::singleton()->stage([
    'title'       => 'Silverstripe Payload Injector',
    'description' => 'Utility for injecting JSON payload into the DOM at render time.'
  ]);
  
  return $this->renderWidth('MainView');
}
```

...in a controller you will have...

```html
    ...
    <script>window.payload = {"title":"Silverstripe Payload Injector","description":"Utility for injecting JSON payload into the DOM at render time."}</script>
  </body>
</html>
```

...in your template.

## Maintainer

- Julian Scheuchenzuber <js@lvl51.de>


