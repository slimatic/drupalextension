<?php

namespace Drupal\DrupalExtension\Compiler;

use Symfony\Component\DependencyInjection\Reference,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Drupal\DrupalExtension container compilation pass.
 */
class DriverPass implements CompilerPassInterface {
  /**
   * Register Drupal drivers.
   */
  public function process(ContainerBuilder $container) {
    if (!$container->hasDefinition('drupal.context')) {
      return;
    }

    $drupalContextDefinition = $container->getDefinition('drupal.context');
    foreach ($container->findTaggedServiceIds('drupal.context.driver') as $id => $attributes) {
      foreach ($attributes as $attribute) {
        if (isset($attribute['alias']) && $name = $attribute['alias']) {
          $drupalContextDefinition->addMethodCall(
            'registerDriver', array($name, new Reference($id))
          );
        }
      }
    }
  }
}
