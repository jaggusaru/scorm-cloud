<?php

/**
 * @file
 * Contains \Drupal\scorm_cloud\Form\ScormCloudSettingForm
 */

namespace Drupal\scorm_cloud\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Configure Scorm cloud settings
 */
class ScormCloudSettingForm extends ConfigFormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'scorm_cloud_setting_form';
    }

    /**
     * {@inheritdoc}
     */
    public function getEditableConfigNames()
    {
        return [
            'scorm_cloud.settings'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('scorm_cloud.settings');
        $form['scorm_cloud_config'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t('SCORM Cloud Configuration Parameters'),
            '#collapsible' => TRUE,
            '#collapsed' => FALSE,
            '#description' => $this->t(''),
        );
        $url = Url::fromUri('http://cloud.scorm.com/');

        $form['scorm_cloud_config']['scorm_cloud_servicesurl'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Services URL'),
            '#description' => $this->t('The SCORM Cloud web services URL. Defaults to @url', array('@url' => $config->get('scorm_cloud_servicesurl'))),
            '#default_value' => $config->get('scorm_cloud_servicesurl') ? $config->get('scorm_cloud_servicesurl') : '',
        );


        $form['scorm_cloud_config']['scorm_cloud_appid'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('AppID'),
            '#default_value' => !empty($config->get('scorm_cloud_appid')) ? $config->get('scorm_cloud_appid') : '',
            '#description' => $this->t('You can generate an App ID at @url.', array('@url' => Link::fromTextAndUrl('http://cloud.scorm.com/', $url)->toString())),
        );

        $form['scorm_cloud_config']['scorm_cloud_secretkey'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Secret Key'),
            '#description' => $this->t('The SCORM Cloud Secret Key. This can be retrieved from @url.', array('@url' => Link::fromTextAndUrl('http://cloud.scorm.com/', $url)->toString())),
            '#default_value' => !empty($config->get('scorm_cloud_secretkey')) ? $config->get('scorm_cloud_secretkey') : '',
        );
        $form['scorm_cloud_registration'] = array(
            '#type' => 'fieldset',
            '#title' => t('SCORM Cloud Course Registration'),
            '#collapsible' => TRUE,
            '#collapsed' => FALSE,
            '#description' => $this->t(''),
        );

        $form['scorm_cloud_registration']['scorm_cloud_registration_email'] = array(
            '#type' => 'select',
            '#title' => $this->t('User Registration Email'),
            '#default_value' => $config->get('scorm_cloud_registration_email'),
            '#options' => array(
                'mail' => $this->t('Account Email Address'),
                // @TODO: Implement the 'ask first time functionality'.
                // 'ask_once' => t('Ask first time'),
                'ask' => $this->t('Ask every time'),
            ),
            '#description' => $this->t('The value to use for an aunthenticated user\'s email when registering for a course.'),
        );

        $form['scorm_cloud_registration']['scorm_cloud_registration_firstname'] = array(
            '#type' => 'select',
            '#title' => $this->t('User Registration First Name'),
            '#default_value' => $config->get('scorm_cloud_registration_firstname'),
            '#options' => array(
                'ask' => $this->t('Ask every time'),
                'name' => $this->t('Drupal username'),
            ),
            '#description' => $this->t('The value to use for an aunthenticated user\'s first name when registering for a course.'),
        );

        $form['scorm_cloud_registration']['scorm_cloud_registration_lastname'] = array(
            '#type' => 'select',
            '#title' => $this->t('User Registration Last Name'),
            '#default_value' => $config->get('scorm_cloud_registration_lastname'),
            '#options' => array(
                'ask' => $this->t('Ask every time'),
                'name' => $this->t('Drupal username'),
            ),
            '#description' => $this->t('The value to use for an aunthenticated user\'s last name when registering for a course.'),
        );
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {

        //$config = $this->config('scorm_cloud.settings');

        $config = \Drupal::service('config.factory')->getEditable('scorm_cloud.settings');
        foreach ($form_state->getValues() as $key => $value) {
            if ($form_state->hasValue($key)) {
                $config->set($key, $form_state->getValue($key));
            }
        }
        $config->save();
        parent::submitForm($form, $form_state);
    }
}