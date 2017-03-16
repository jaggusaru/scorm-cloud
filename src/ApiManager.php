<?php

namespace Drupal\scorm_cloud;

class ApiManager
{
    
    const SCORM_CLOUD_ORIGIN = 'drupal.scorm_cloud.1.0';

    /**
     * Create an api engine for calling the api.
     *
     * @return ScormEngineService
     * scorm_cloud_api_service_engine
     */
    protected function apiServiceEngine()
    {
        $path = $this->getApiLibraryPath();
        //$path = "C:/xampp/htdocs/drupal-8.2.6/libraries/scorm_cloud/ScormEngineService.php";

        if ($path) {
            $cofig = \Drupal::config('scorm_cloud.settings');

            require_once(DRUPAL_ROOT . '/' . $path . '/ScormEngineService.php');
            $appid = $cofig->get('scorm_cloud_appid');
            $secretkey = $cofig->get('scorm_cloud_secretkey');
            $engine_url = $cofig->get('scorm_cloud_servicesurl');

            return new \ScormEngineService($engine_url, $appid, $secretkey, self::SCORM_CLOUD_ORIGIN);
        } else {
            drupal_set_message(t('You must install the SCORM Cloud PHP libary. Please see the README.txt file in the SCORM Cloud module directory.'), 'error', FALSE);
        }
    }

    /**
     * Call a method on a given service.
     *
     * @param $method
     * @param null $params
     * @return mixed
     *
     *  This function does the actual call.
     * _scorm_cloud_api_call_method
     */
    public function callAPIMethod($method, $params = NULL)
    {
        try {
            $scorm_cloud_service = $this->apiServiceEngine();
            if ($scorm_cloud_service) {
                $request = $scorm_cloud_service->CreateNewRequest();

                if ($request) {
                    $request->setMethodParams($params);
                    $out = $request->CallService($method);
                    $out = $this->apiParseResponse($out, $method, $params);
                    return $out;
                }
            }
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
    }

    /**
     * Call a method on a given service.
     *
     * This function does the actual call.
     * _scorm_cloud_api_call
     */
    public function callApi($service, $method, $params = NULL)
    {
        try {
            $scorm_cloud_service = $this->apiServiceEngine();
            if ($scorm_cloud_service) {
                $get_service_method = 'get' . $service . 'Service';

                if (method_exists($scorm_cloud_service, $get_service_method)) {
                    $service_object = $scorm_cloud_service->{$get_service_method}();

                    if (method_exists($service_object, $method)) {
                        $out = call_user_func_array(array($service_object, $method), (array)$params);
                        $out = $this->apiParseResponse($out, $method, $params);
                        return $out;
                    }
                    trigger_error(t('SCORM Cloud method %method could not be found on service %service.', array('%service' => $service, '%method' => $method)), E_USER_WARNING);
                    return NULL;
                }
                trigger_error(t('SCORM Cloud service %service could not be found. Requested method was %method.', array('%service' => $service, '%method' => $method)), E_USER_WARNING);
            }
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
        return NULL;
    }

    /**
     * Parse the API response
     *
     * @param $out
     * @return \SimpleXMLElement
     * _scorm_cloud_api_parse_response
     */
    protected function apiParseResponse($out)
    {
        // Quick and dirty way of determining if the results are XML.
        if (is_string($out) && strpos($out, '<?xml version="1.0" encoding="utf-8" ?>') !== false) {
            $xml = simplexml_load_string($out);
            return $xml;
        }
        return $out;
    }

    /**
     * Determine the path to the SCORM Cloud php library.
     *
     * @return string
     * scorm_cloud_api_library_path
     */
    protected function getApiLibraryPath()
    {
        // A path can be configured to the location of the library.
        $config = \Drupal::config('scorm_cloud.settings');
        $path = $config->get('scorm_cloud_library_path');

        if (!$path) {
            // Check if the libraries module is installed and if the example library is
            // being supplied through the libraries module.
            if (\Drupal::moduleHandler()->moduleExists(libraries)) {
                // Check if the library is found. If no library is found libraries_get_path()
                // will still return sites/all/libraries as a path.
                $libraries = libraries_get_libraries();
                if (isset($libraries['scorm_cloud'])) {
                    $path = libraries_get_path('scorm_cloud');
                }
            }
            if (file_exists('libraries/scorm_cloud/ScormEngineService.php')) {
                return 'libraries/scorm_cloud';
            }
            // Look for the library in sites/all/libraries and sites/default/libraries
            if (file_exists('sites/default/libraries/scorm_cloud/ScormEngineService.php')) {
                return 'sites/default/libraries/scorm_cloud';
            }
            if (file_exists('sites/all/libraries/scorm_cloud/ScormEngineService.php')) {
                return 'sites/all/libraries/scorm_cloud';
            }

        }
        return $path;
    }

}