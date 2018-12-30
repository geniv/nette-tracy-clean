Tracy clean
===========

Installation
------------

```sh
$ composer require geniv/nette-tracy-clean
```
or
```json
"geniv/nette-tracy-clean": "^1.0"
```

require:
```json
"php": ">=7.0",
"nette/nette": ">=2.4"
```

Include in application
----------------------
neon configure:
```neon
# tracy clean
tracyClean:
#    tracyClean: true
#    disableDebug: true
#    error404: true
#    error500: true
#    error503: true
#    maintenance: true
#    roles: @identityAuthorizatorAdmin.driver::getRoles()
#    roles:
#        - guest
#        - moderator
#        - admin
#    tracyCleanDirs:
#        - temp/cache
#        - temp/sessions
#        - admin/temp/cache
#        - admin/temp/sessions
    link:
        Old clean:
            url: "clean.php"
            target: _blank
            confirm: "Opravdu promazat?"
        Admin:
            url: "admin/"
            target: _blank
#        Admin2:
#            url: "admin/"
#            target: _self
#        Admin: "admin/"
#        Pokus:
#            link: Homepage:vzor

## OR

    roles: @identityAuthorizatorAdmin.driver::getRole()
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
