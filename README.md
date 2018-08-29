# ApiVersioningBundle

Informations
============
[![Build Status](https://travis-ci.org/XuruDragon/ApiVersioningBundle.svg?branch=master)](https://travis-ci.org/XuruDragon/ApiVersioningBundle) - master

This bundle provide you a way to handle multiple version of your api.
See `Usage` section to know how ise this bundle

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require xurudragon/api-versioning-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
If you are on `Symfony 3.*` look in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new XuruDragon\ApiVersioningBundle\XuruDragonApiVersioningBundle(),
        );

        // ...
    }

    // ...
}
```

If you are on `Symfony 4.*` look in the `config/bundles.php` file of your project:

```php
<?php

//config/bundles.php

return [
    // ....
    XuruDragon\ApiVersioningBundle\XuruDragonApiVersioningBundle::class => ['all' => true],
];
```

Step 3: Configuration
-------------------------

Nothing special to do here. Unless you want to change the header name sent to the api to processing versioning changes
You can add theses entries into your config files :

```yaml
xurudragon_versioning:
    # Header name that should be sent to the api to processing versioning changes
    header_name: X-Accept-Version #Header name
```

Usage
=====

Introduction
-------------------------
Your API reponse must always match the latest version of your API.

To support multiple versions, you must for each version of your API that breaks the backward compatibility:
1. create a change class inheriting from the following abstract class: `XuruDragon\ApiVersioningBundle\Changes\AbstractChanges`
2. add an entry in the configuration file corresponding to the class created previously


we recommend to you the following structure:
1. Create a folder named `ApiChanges`
2. Name your classes `ApiChangesXYZ`, with `X` for the `Major` version, `Y` for the `Minor` version and `Z` for the `Patch` version

We assume that you know the [semantic versioning system](http://semver.org)

Create the Change Class
-------------------------

Here is the _0.9.0_ class version, which assumes that your API version is at least _0.9.1_ .

```php
<?php
// ../ApiChanges/ApiChanges090.php

namespace Your\Bundle\ApiChanges;

use XuruDragon\ApiVersioningBundle\Changes\AbstractChanges,

class ApiChanges090 extends AbstractChanges
{

    /**
     * Apply version changes for current request.
     *
     * @param array $data
     *
     * @return null|array
     */
    public function apply(array $data = [])
    {
        return $data;
    }

    /**
     * Returns true if this version changes is supported for current request.
     *
     * @param array $data
     *
     * @return null|bool
     */
    public function supports(array $data = [])
    {
        return true;
    }
    // ...
}
```

You should now implement the apply and supports methods with your own logic.

`supports` should return `true` or `false` following if this version change support the actual data returned by your latest api version.

`apply` should do whatever you want to transform the actual data into an version that correspond to this api version. The  returned array would be encoded in json and return to the api client that have make the request.

####Example
Imagine that version 1.0.0 of your API adds an entry to the json output of one of the called services.
This entry would be "location" within the following data array returned by your latest API version :

```php
$data = [
    'results' => [
        'firstname' => 'John',
        'lastname' => 'Doe',
        'location' => '4, Privet Drive, Little Whinging, Surey', 
    ],
];
```

You must then delete this data entry so that the requested version 0.9.0 matches what the calling client is waiting for.

You can do that by modifying the previously created class with the following code :

```php
<?php
// ../ApiChanges/ApiChanges090.php

namespace Your\Bundle\ApiChanges;

use XuruDragon\ApiVersioningBundle\Changes\AbstractChanges,

/**
 * Class ApiChanges090.
 */
class ApiChanges090 extends AbstractChanges
{
    /**
     * {@inheritdoc}
     */
    public function apply(array $data = [])
    {
        //short example, but can be more more complex
        unset($data['results']['location']);

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(array $data = [])
    {
        //check for the entry column "location" in the results sub-array of $data
        return isset($data['results']) && array_search('location', array_keys($data['results']), true);
    }
    // ...
}
```

Of course here it's a pretty simple example of delete One entry, the processing could be more more complicated.

Register your version changes class in the configuration
-------------------------
```yaml
    # Array of version changes that breaks backward compatibility
    # The array should be `desc` ordered.
    # The newest version should be the first and the oldest the last one
    versions:
        # version 0.9.0
        - { version_number: "0.9.0", changes_class: "Your\Bundle\ApiChanges\ApiChanges090"}
```

Here you are, no more configuration is required.
