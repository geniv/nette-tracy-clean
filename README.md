Tracy clean
===========

Installation
------------

```sh
$ composer require geniv/nette-tracy-clean
```
or
```json
"geniv/nette-tracy-clean": ">=1.0.0"
```

require:
```json
"php": ">=5.6.0",
"nette/nette": ">=2.4.0"
```

Include in application
----------------------
neon configure:
```neon
# tracy clean
tracyClean:
    TracyClean: true
    DisableDebug: true
    Error404: true
    Error500: true
    Error503: true
    Maintenance: true
    link:
        Old clean:
            url: "clean.php"
            target: _blank
            confirm: "Opravdu promazat?"
        Admin new window:
            url: "admin/"
            target: _blank
        Admin2:
            url: "admin/"
            target: _self
        Admin: "admin/"
        Pokus:
            link: Homepage:vzor
```

neon configure extension (it is possible use anonymous extension):
```neon
extensions:
    tracyClean: TracyClean\Bridges\Nette\Extension
#   - TracyClean\Bridges\Nette\Extension
```

presenters (in case use named extension):
```php
use TracyClean\TracyClean;

class BasePresenter extends Presenter
{
    use AutowiredComponent;
    use TracyClean;
```
