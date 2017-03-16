<?php

namespace Drupal\scorm_cloud\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Registration entity.
 *
 * @ingroup scorm_cloud
 *
 * @ContentEntityType(
 *   id = "registration",
 *   label = @Translation("Registration"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\scorm_cloud\RegistrationListBuilder",
 *     "views_data" = "Drupal\scorm_cloud\Entity\RegistrationViewsData",
 *     "translation" = "Drupal\scorm_cloud\RegistrationTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\scorm_cloud\Form\RegistrationForm",
 *       "add" = "Drupal\scorm_cloud\Form\RegistrationForm",
 *       "edit" = "Drupal\scorm_cloud\Form\RegistrationForm",
 *       "delete" = "Drupal\scorm_cloud\Form\RegistrationDeleteForm",
 *     },
 *     "access" = "Drupal\scorm_cloud\RegistrationAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\scorm_cloud\RegistrationHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "registration",
 *   data_table = "registration_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer registration entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/registration/{registration}",
 *     "add-form" = "/admin/structure/registration/add",
 *     "edit-form" = "/admin/structure/registration/{registration}/edit",
 *     "delete-form" = "/admin/structure/registration/{registration}/delete",
 *     "collection" = "/admin/structure/registration",
 *   },
 *   field_ui_base_route = "registration.settings"
 * )
 */
class Registration extends ContentEntityBase implements RegistrationInterface
{

    use EntityChangedTrait;

    /**
     * {@inheritdoc}
     */
    public static function preCreate(EntityStorageInterface $storage_controller, array &$values)
    {
        parent::preCreate($storage_controller, $values);
        $values += array(
            'user_id' => \Drupal::currentUser()->id(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->get('name')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->set('name', $name);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedTime()
    {
        return $this->get('created')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedTime($timestamp)
    {
        $this->set('created', $timestamp);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwner()
    {
        return $this->get('user_id')->entity;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwnerId()
    {
        return $this->get('user_id')->target_id;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwnerId($uid)
    {
        $this->set('user_id', $uid);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwner(UserInterface $account)
    {
        $this->set('user_id', $account->id());
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isPublished()
    {
        return (bool)$this->getEntityKey('status');
    }

    /**
     * {@inheritdoc}
     */
    public function setPublished($published)
    {
        $this->set('status', $published ? TRUE : FALSE);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCourseId()
    {
        return $this->get('course_id')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setCourseId($course_id)
    {
        $this->set('course_id', $course_id);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDateFirstLaunched()
    {
        return $this->get('date_first_launched')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setDateFirstLaunched($date_first_launched)
    {
        $this->set('date_first_launched', $date_first_launched);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDateLastLaunched()
    {
        return $this->get('date_last_launched')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setDateLastLaunched($date_last_launched)
    {
        $this->set('date_last_launched', $date_last_launched);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDateCompleted()
    {
        return $this->get('date_completed')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setDateCompleted($date)
    {
        $this->set('date_completed', $date);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIsCompleted()
    {
        return $this->get('complete')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setIsCompleted($complete)
    {
        return $this->set('complete', $complete);
    }

    /**
     * {@inheritdoc}
     */
    public function getSuccess()
    {
        return $this->get('date_completed')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setSuccess($date)
    {
        return $this->set('date_completed', $date);
    }

    /**
     * {@inheritdoc}
     */
    public function getScore()
    {
        return $this->get('score')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setScore($score)
    {
        return $this->set('score', $score);
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalTime()
    {
        return $this->get('totaltime')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setTotalTime($score)
    {
        return $this->set('totaltime', $score);
    }

    /**
     * {@inheritdoc}
     */
    public function getNumLaunches()
    {
        return $this->get('num_launches')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setNumLaunches($score)
    {
        return $this->set('num_launches', $score);
    }

    /**
     * {@inheritdoc}
     */
    public function getTakeNumber()
    {
        return $this->get('take_number')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setTakeNumber($score)
    {
        return $this->set('take_number', $score);
    }

    /**
     * {@inheritdoc}
     */
    public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
    {
        $fields = parent::baseFieldDefinitions($entity_type);
        $fields['course_id'] = BaseFieldDefinition::create('string')
            ->setLabel(t('Course ID'))
            ->setDescription(t('The course id that this registration is for'))
            ->setRequired(FALSE);

        $fields['date_first_launched'] = BaseFieldDefinition::create('integer')
            ->setLabel(t('Date First Lunched'))
            ->setDescription(t('The date that the course was first launched. Retrieved via the API.'))
            ->setRequired(FALSE);

        $fields['date_last_launched'] = BaseFieldDefinition::create('integer')
            ->setLabel(t('Date Last Lunched'))
            ->setDescription(t('The date that the course was last launched. Retrieved via the API.'))
            ->setRequired(FALSE);

        $fields['date_completed'] = BaseFieldDefinition::create('integer')
            ->setLabel(t('Date Completed'))
            ->setDescription(t('The date that the course was completed. Retrieved via the API.'))
            ->setRequired(FALSE);

        $fields['complete'] = BaseFieldDefinition::create('string')
            ->setLabel(t('is Completed'))
            ->setDescription(t('Whether or not the course was completed. Retrieved via the API.'))
            ->setSettings(array(
                'max_length' => 255,
                'text_processing' => 0,
            ))
            ->setRequired(TRUE);

        $fields['success'] = BaseFieldDefinition::create('string')
            ->setLabel(t('is Passed'))
            ->setDescription(t('Whether or not the course was passed. Retrieved via the API.'))
            ->setSettings(array(
                'max_length' => 255,
                'text_processing' => 0,
            ))
            ->setRequired(TRUE);

        $fields['score'] = BaseFieldDefinition::create('integer')
            ->setLabel(t('Score'))
            ->setDescription(t('The score earned. Retrieved via the API.'))
            ->setRequired(TRUE);

        $fields['totaltime'] = BaseFieldDefinition::create('integer')
            ->setLabel(t('Total Time'))
            ->setDescription(t('Time spent taking the course. Retrieved via the API.'))
            ->setRequired(TRUE);

        $fields['num_launches'] = BaseFieldDefinition::create('integer')
            ->setLabel(t('Number of time course launched'))
            ->setDescription(t('The number of times that the course was launched. Retrieved via the API.'))
            ->setRequired(TRUE);

        $fields['take_number'] = BaseFieldDefinition::create('integer')
            ->setLabel(t('Take Number'))
            ->setDescription(t('The sequence number of the registration if multiple exist for a user and course.'))
            ->setRequired(TRUE);
        $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
            ->setLabel(t('Authored by'))
            ->setDescription(t('The user ID of author of the Registration entity.'))
            ->setRevisionable(TRUE)
            ->setSetting('target_type', 'user')
            ->setSetting('handler', 'default')
            ->setTranslatable(TRUE)
            ->setDisplayOptions('view', array(
                'label' => 'hidden',
                'type' => 'author',
                'weight' => 0,
            ))
            ->setDisplayOptions('form', array(
                'type' => 'entity_reference_autocomplete',
                'weight' => 5,
                'settings' => array(
                    'match_operator' => 'CONTAINS',
                    'size' => '60',
                    'autocomplete_type' => 'tags',
                    'placeholder' => '',
                ),
            ))
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);

        $fields['name'] = BaseFieldDefinition::create('string')
            ->setLabel(t('Name'))
            ->setDescription(t('The name of the Registration entity.'))
            ->setSettings(array(
                'max_length' => 50,
                'text_processing' => 0,
            ))
            ->setDefaultValue('')
            ->setDisplayOptions('view', array(
                'label' => 'above',
                'type' => 'string',
                'weight' => -4,
            ))
            ->setDisplayOptions('form', array(
                'type' => 'string_textfield',
                'weight' => -4,
            ))
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);

        $fields['status'] = BaseFieldDefinition::create('boolean')
            ->setLabel(t('Publishing status'))
            ->setDescription(t('A boolean indicating whether the Registration is published.'))
            ->setDefaultValue(TRUE);

        $fields['created'] = BaseFieldDefinition::create('created')
            ->setLabel(t('Created'))
            ->setDescription(t('The time that the entity was created.'));

        $fields['changed'] = BaseFieldDefinition::create('changed')
            ->setLabel(t('Changed'))
            ->setDescription(t('The time that the entity was last edited.'));

        return $fields;
    }

}
