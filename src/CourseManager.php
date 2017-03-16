<?php

namespace Drupal\scorm_cloud;

use Drupal\scorm_cloud\ApiManager;
//use Symfony\Component\DependencyInjection\ContainerInterface; 
use Drupal\Core\Url;

class CourseManager
{

    public function __construct(ApiManager $apiManager)
    {
        $this->apiManager = $apiManager;
    }

    /*
    public static function create(ContainerInterface $container) {
            return new static(
                $container->get('scorm_cloud.api_manager')
            );
        }
        */
    /**
     * Course listing page.
     * scorm_cloud_admin_courses_list
     */
    public function getCoursesList()
    {
        $rows = array();
        $headers = array();

        $api_courses = $this->getCoursesApi();

        //echo is_null($api_courses) ? "NULL" : "not null";exit();
        $drupal_courses = []; //$this->getCoursesDrupal();

        $course_fields = array(
            '_courseId' => t('ID'),
            '_title' => t('Title'),
            '_numberOfVersions' => t('Versions'),
            '_numberOfRegistrations' => t('Registrations'),
        );
        $headers = array(
            t('Course ID'),
            t('Title'),
            t('Versions'),
            t('Registration'),
            t('In Scorm CLOUD'),
            t('In Drupal'),
        );

        // List the courses in the SCORM Cloud API
        foreach ($api_courses as $course_obj) {
            $course_id = $course_obj->getCourseId();

            $course = array();
            $course['id'] = $course_obj->getCourseId();
            $course['title'] = $course_obj->getTitle();
            $course['versions'] = $course_obj->getNumberOfVersions();
            $course['registrations'] = $course_obj->getNumberOfRegistrations();
            $course['in_scorm'] = array(
                'data' => t('Yes'),
                'class' => 'error',
            );
            $course['in_drupal'] = array(
                'data' => t('No'),
                'class' => 'error',
            );

            if (isset($drupal_courses[$course_id])) {
                $course['in_drupal'] = array('data' => t('Yes') . ' (' . \Drupal::l(t('view'), Url::fromRoute('node/' . $drupal_courses[$course_id]['nids'][0])) . ')');
                $course['in_scorm']['class'] = $course['in_drupal']['class'] = 'ok';
                unset($drupal_courses[$course_id]);
            } else {
                // Add delete link.
                $course['in_scorm']['data'] .= ' (' . \Drupal::l(t('delete'), Url::fromRoute('scorm_cloud.settings')) . ')'; //'admin/config/scorm_cloud/courses/delete/' . $course_obj->getCourseId()) . ')';
            }

            $rows[] = $course;
        }

        // List the courses in drupal but not in SCORM.
        foreach ($drupal_courses as $course_id => $course_array) {
            $course = array();
            $course['id'] = $course_id;
            $course['title'] = empty($course_array['title']) ? '-' : $course_array['title'];
            $course['versions'] = '-';
            $course['registrations'] = '-';
            $course['in_scorm'] = array(
                'data' => t('No'),
                'class' => 'error',
            );

            foreach ($course_array['nids'] as $nid) {
                $course['in_drupal'] = array(
                    'data' => t('Yes') . ' (' . l(t('view'), 'node/' . $nid) . ' | ' . l(t('delete'), 'node/' . $nid . '/delete', array('query' => drupal_get_destination())) . ')',
                    'class' => 'error',
                );
                $rows[] = $course;
            }
        }
        return [
            '#theme' => 'table',
            '#header' => $headers,
            '#rows' => $rows,
            '#empty' => t('No course found'),
        ];
        //return theme('table', array('header' => $headers, 'rows' => $rows));
    }

    /**
     * Course listing from the API.
     * scorm_cloud_admin_get_courses_api
     */
    protected function getCoursesApi()
    {
        static $courses = NULL;

        if ($courses === NULL) {
            $courses = $this->apiManager->callApi('Course', 'getCourseList');
        }
        return $courses;
    }

    /**
     * Course listing from the API.
     * scorm_cloud_admin_get_courses_drupal
     */
    protected function getCoursesDrupal()
    {
        //return scorm_cloud_get_course_list();
        return [];
    }
}