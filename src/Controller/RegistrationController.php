<?php

namespace Drupal\scorm_cloud\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\scorm_cloud\Entity\RegistrationInterface;
use Drupal\scorm_cloud\RegistrationManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RegistrationController.
 *
 *  Returns responses for Registration routes.
 *
 * @package Drupal\scorm_cloud\Controller
 */
class RegistrationController extends ControllerBase
{

    public function getRegistrations()
    {
//echo 'hello';
        //exit();
        //echo '<pre>';
        //print_r($_SERVER);exit();
        $registrationManager = \Drupal::service('scorm_cloud.registration_manager');
        return $registrationManager->getRegistrationsList();
    }
}
