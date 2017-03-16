<?php
namespace Drupal\scorm_cloud\Controller;

use Drupal\scorm_cloud\CourseManager;

class CourseController
{

    public function getCourses()
    {

        $courseManager = \Drupal::service('scorm_cloud.course_manager');
        return $courseManager->getCoursesList();
    }
}