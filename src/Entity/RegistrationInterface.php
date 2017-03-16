<?php

namespace Drupal\scorm_cloud\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Registration entities.
 *
 * @ingroup scorm_cloud
 */
interface RegistrationInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface
{

    // Add get/set methods for your configuration properties here.

    /**
     * Gets the Registration name.
     *
     * @return string
     *   Name of the Registration.
     */
    public function getName();

    /**
     * Sets the Registration name.
     *
     * @param string $name
     *   The Registration name.
     *
     * @return \Drupal\scorm_cloud\Entity\RegistrationInterface
     *   The called Registration entity.
     */
    public function setName($name);

    /**
     * Gets the Registration creation timestamp.
     *
     * @return int
     *   Creation timestamp of the Registration.
     */
    public function getCreatedTime();

    /**
     * Sets the Registration creation timestamp.
     *
     * @param int $timestamp
     *   The Registration creation timestamp.
     *
     * @return \Drupal\scorm_cloud\Entity\RegistrationInterface
     *   The called Registration entity.
     */
    public function setCreatedTime($timestamp);

    /**
     * Returns the Registration published status indicator.
     *
     * Unpublished Registration are only visible to restricted users.
     *
     * @return bool
     *   TRUE if the Registration is published.
     */
    public function isPublished();

    /**
     * Sets the published status of a Registration.
     *
     * @param bool $published
     *   TRUE to set this Registration to published, FALSE to set it to unpublished.
     *
     * @return \Drupal\scorm_cloud\Entity\RegistrationInterface
     *   The called Registration entity.
     */
    public function setPublished($published);

    /**
     * Gets the Registration courseid.
     *
     * @return int
     *   Course id.
     */
    public function getCourseId();

    /**
     * Sets the Registration creation timestamp.
     *
     * @param int $timestamp
     *   The Registration creation timestamp.
     *
     * @return \Drupal\scorm_cloud\Entity\RegistrationInterface
     *   The called Registration entity.
     */
    public function setCourseId($courseId);

    /**
     * Gets the Registration creation timestamp.
     *
     * @return int
     *   Creation timestamp of the Registration.
     */
    public function getDateFirstLaunched();

    public function setDateFirstLaunched($date);

    /**
     * Sets the Registration creation timestamp.
     *
     * @param int $timestamp
     *   The Registration creation timestamp.
     *
     * @return \Drupal\scorm_cloud\Entity\RegistrationInterface
     *   The called Registration entity.
     */
    public function getDateLastLaunched();

    public function setDateLastLaunched($date);

    /**
     * Gets the Registration creation timestamp.
     *
     * @return int
     *   Creation timestamp of the Registration.
     */
    public function getDateCompleted();

    /**
     * Sets the Registration creation timestamp.
     *
     * @param int $timestamp
     *   The Registration creation timestamp.
     *
     * @return \Drupal\scorm_cloud\Entity\RegistrationInterface
     *   The called Registration entity.
     */
    public function setDateCompleted($date);

    public function getIsCompleted();

    public function setIsCompleted($complete);

    /**
     * Gets the Registration creation timestamp.
     *
     * @return int
     *   Creation timestamp of the Registration.
     */
    public function getSuccess();

    /**
     * Sets the Registration creation timestamp.
     *
     * @param int $timestamp
     *   The Registration creation timestamp.
     *
     * @return \Drupal\scorm_cloud\Entity\RegistrationInterface
     *   The called Registration entity.
     */
    public function setSuccess($success);

    /**
     * Gets the Registration creation timestamp.
     *
     * @return int
     *   Creation timestamp of the Registration.
     */
    public function getScore();

    /**
     * Sets the Registration creation timestamp.
     *
     * @param int $timestamp
     *   The Registration creation timestamp.
     *
     * @return \Drupal\scorm_cloud\Entity\RegistrationInterface
     *   The called Registration entity.
     */
    public function setScore($score);

    public function getTotalTime();

    /**
     * Sets the Registration creation timestamp.
     *
     * @param int $timestamp
     *   The Registration creation timestamp.
     *
     * @return \Drupal\scorm_cloud\Entity\RegistrationInterface
     *   The called Registration entity.
     */
    public function setTotalTime($time);

    public function getNumLaunches();

    /**
     * Sets the Registration creation timestamp.
     *
     * @param int $timestamp
     *   The Registration creation timestamp.
     *
     * @return \Drupal\scorm_cloud\Entity\RegistrationInterface
     *   The called Registration entity.
     */
    public function setNumLaunches($launch);

    public function getTakeNumber();

    /**
     * Sets the Registration creation timestamp.
     *
     * @param int $timestamp
     *   The Registration creation timestamp.
     *
     * @return \Drupal\scorm_cloud\Entity\RegistrationInterface
     *   The called Registration entity.
     */
    public function setTakeNumber($takeNumber);

}
