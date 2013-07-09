# Symfony Facebook Bundle

This bundle provides **ready to use official Facebook SDK client** and common Facebook configuration (implements *PrependExtensionInterface*, see: http://symfony.com/doc/current/cookbook/bundles/prepend_extension.html) for any Symfony bundle.

Facebook SDK client is **integrated with Symfony session storage** (unlike vanilla client which uses native PHP sessions).

## Bundles Using This Bundle

* [laelaps/symfony-facebook-authentication-bundle](https://github.com/laelaps/symfony-facebook-authentication-bundle) (of course)

If you are using this bundle, contact me or update README in your pull request.

## For Facebook SDK Users

### Using Facebook SDK

```YAML
# config.yml

facebook:
    application_id: "your_application_id"
    secret: "your_application_secret"
```

```PHP
// Controllers/YourSymfonyController.php

class YourSymfonyController extends Controller
{
    function indexAction()
    {
        $readyToUseFacebookSdk = $this->get('facebook');
        // ... done
    }
}
```


### Using Extended Facebook SDK Configuration

```YAML
# config.yml

facebook:
    application_id: "your_application_id"
    secret: "your_application_secret"
    file_upload: true # indicate if your server configuration allows CURL @ file uploads
    permissions: # see: https://developers.facebook.com/docs/reference/login/#permissions
        - publish_actions
        - user_games_activity
        - ... etc
    trust_proxy_headers: true # Facebook SDK now trusts EVERY HTTP_X_FORWARDED_* header
```

## For Symfony Bundle Developers

### Automating Your Bundle Facebook Configuration With Laelaps Bundle

```PHP
// Appkernel.php

class AppKernel
{
    public function registerBundles()
    {
        return [
            // ...
            new \Laelaps\Bundle\Facebook\FacebookBundle,
            // ...
        ];
    }
}
```

```YAML
# config.yml

facebook:
    application_id: "your_application_id"
    secret: "your_application_secret"
```

```PHP
// DependencyInjection/YourExtension.php

use Laelaps\Bundle\Facebook\FacebookExtensionInterface;
use Laelaps\Bundle\Facebook\FacebookExtensionTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class YourExtension extends Extension implements FacebookExtensionInterface
{
    use FacebookExtensionTrait;

    public function load(array $configs, ContainerBuilder $container)
    {
        print_r($configs);
        /*
            (
                [application_id] => example_application_id
                [secret] => example_secret
                [file_upload] =>
                [permissions] => Array
                    (
                    )

                [trust_proxy_headers] =>
            )
        */
    }
}
```


### Prefixing Configuration

```PHP
// DependencyInjection/YourExtension.php

use Laelaps\Bundle\Facebook\FacebookExtensionInterface;
use Laelaps\Bundle\Facebook\FacebookExtensionTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class YourExtension extends Extension implements FacebookExtensionInterface
{
    use FacebookExtensionTrait;

    public function getFacebookConfigurationPrefix(array $config, ContainerBuilder $container)
    {
        return 'facebook_';
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        print_r($configs[0]);
        /*
            (
                [facebook_application_id] => example_application_id
                [facebook_secret] => example_secret
                [facebook_file_upload] =>
                [facebook_permissions] => Array
                    (
                    )

                [facebook_trust_proxy_headers] =>

            )
        */
    }
}
```

## Semantic Versioning

This repository follows [Semantic Versioning 2.0.0](http://semver.org/).
