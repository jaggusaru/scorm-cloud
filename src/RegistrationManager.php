<?php

namespace Drupal\scorm_cloud;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\scorm_cloud\ApiManager;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Url;

/**
 * Class RegistrationManager
 * @package Drupal\scorm_cloud
 */
class RegistrationManager
{

    /**
     * @var ApiManager
     */
    protected $apiManager;

    /**
     * @var $entityQuery
     */
    protected $entityQuery;

    /**
     * @param ApiManager $apiManager
     * @param QueryFactory $entityQuery
     * @param EntityTypeManagerInterface $entityManager
     */
    public function __construct(ApiManager $apiManager, QueryFactory $entityQuery, EntityTypeManagerInterface $entityManager)
    {
        $this->apiManager = $apiManager;
        $this->entityQuery = $entityQuery;
        $this->entityManager = $entityManager;

    }

    /**
     * @param ContainerInterface $container
     * @return static
     */
    /*
    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('scorm_cloud.api_manager'),
			 $container->get('entity.query'),
			 $container->get('entity.manager')
            

        );
    }*/
    /**
     * @param null $course_id
     * @return mixed
     * scorm_cloud_admin_registrations_list
     */
    public function getRegistrationsList($course_id = NULL)
    {
        $rows = array();

        $headers = array(
            'registrationId',
            'courseId',
            'courseTitle',
            'learnerId',
            'learnerFirstName',
            'learnerLastName',
            'email',
            'createDate',
            t('In SCORM Cloud'),
            t('In Drupal'),
        );

        $drupal_registrations = $this->getRegistrationsDrupal($course_id);

        $items = $this->getRegistrationsApi($course_id);
        if (!empty($items)) {
            foreach ($items as $item) {
                //$item = $item->registration;
                $row = array();
                foreach ($headers as $key) {
                    $row[$key] = empty($item->{$key}) ? '' : (string)$item->{$key};
                }

                $row[t('In SCORM Cloud')] = array(
                    'data' => t('Yes'),
                    'class' => 'error',
                );
                $row[t('In Drupal')] = array(
                    'data' => t('No'),
                    'class' => 'error',
                );

                if (isset($drupal_registrations[$row['registrationId']])) {
                    $row[t('In Drupal')] = array('data' => t('Yes'));
                    $row[t('In Drupal')]['class'] = $row[t('In SCORM Cloud')]['class'] = 'ok';
                    unset($drupal_registrations[$row['registrationId']]);
                } else {
                    $row[t('In SCORM Cloud')]['data'] .= ' (' . \Drupal::l('Delete', Url::fromRoute('scorm_cloud.settings'));
                    // $row[t('In SCORM Cloud')]['data'] .= ' (' . \Drupal::l(t('delete'), 'admin/config/scorm_cloud/registrations/delete/' . $row['registrationId']) . ')';
                }

                $rows[] = $row;
            }
        }

        foreach ($drupal_registrations as $id => $reg) {
            $row = array(
                'registrationId' => $id,
                'course_id' => $course_id,
                'courseTitle' => '-',
                'learnerId' => $reg->uid,
                'learnerFirstName' => '',
                'learnerLastName' => '',
                'email' => '',
                'createDate' => \Drupal::service('date.formatter')->format($reg->date_first_launched, 'short'),
                'in_scorm' => array(
                    'data' => t('No'),
                    'class' => 'error',
                ),
                'in_drupal' => array(
                    'data' => t('Yes') . ' (' . \Drupal::l(t('Delete', Url::fromRoute('scorm_cloud.settings'))),
                    //'data' => t('Yes') . ' (' . Drupal::l(t('delete'), 'admin/config/scorm_cloud/registrations/delete/' . $id) . ')',
                    'class' => 'error',
                ),
            );
            $rows[] = $row;
        }
        return [
            '#theme' => 'table',
            '#rows' => $rows,
            '#header' => $headers,
            '#empty' => t('No Registration Found.')

        ];
        // return theme('table', array('header' => $headers, 'rows' => $rows));
    }

    /**
     * @param string $course_id
     * @return mixed
     * scorm_cloud_admin_get_registrations_drupal
     */
    public function getRegistrationsDrupal($course_id = '')
    {
        static $registrations = array();

        if ($course_id) {
            return $this->getCourseRegistrations($course_id);
        } else {
            return $this->getRegistrations();
        }
    }

    /**
     * @param string $course_id
     * @return mixed
     * Registration listing from the API.
     * scorm_cloud_admin_get_registrations_api
     */
    public function getRegistrationsApi($course_id = '')
    {
        static $registrations = array();

        if (!isset($registrations[$course_id])) {
            $params = array();
            if ($course_id) {
                $params['coursefilter'] = $course_id;
            }
            $items = $this->apiManager->callAPIMethod('rustici.registration.getRegistrationList', $params);
            $registrations[$course_id] = isset($items->registrationlist->registration) ? $items->registrationlist->registration : array();
        }
        return $registrations[$course_id];
    }

    /**
     * @param null $course_id
     * @return array
     *
     * Retrieve the user registration for the given course from the database
     * scorm_cloud_get_registrations
     */

    public function getRegistrations($course_id = NULL)
    {
        $out = array();
        $nids = $this->entityQuery->get('registration')
            ->sort('date_first_launched')
            ->execute();
        $results = $this->entityManager->getStorage('registration')->loadMultiple($nids); //$this->database->query('SELECT * FROM {registration} ORDER BY date_first_launched ASC')->fetchAll();
        //->fetchAllAssoc();


        foreach ($results as $registration) {
            $out[$registration->id] = $registration;
        }
        return $out;
    }

    /**
     * @param $course_id
     * @return array
     * scorm_cloud_get_course_registrations
     */
    public function getCourseRegistrations($course_id)
    {
        $out = array();
        $nids = $this->entityQuery->get('registration')
            ->condition('course_id', $course_id)
            ->sort('date_first_launched')
            ->execute();
        $results = $results = $this->entityManager->getStorage('registration')->loadMultiple($nids); //$this->database->query('SELECT * from {registration} WHERE course_id = :course_id ORDER BY date_first_launched ASC', array(':course_id' => $course_id))->fetchAll();
        foreach ($results as $registration) {
            $out[$registration->id] = $registration;
        }
        return $out;
    }

}