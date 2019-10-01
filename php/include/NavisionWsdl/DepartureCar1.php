<?php

namespace nav;

use Exception;
use SoapClient;
use SoapFault;

class DepartureCar1 extends SoapClient
{
    private $login;
    private $password;
    private $location;

    private $requestRetry = 0;

    /**
     * @var array $classmap The defined classes
     */
    private static $classmap = array(
        'CheckDepatureCar' => 'nav\\CheckDepatureCar',
        'CheckDepatureCar_Result' => 'nav\\CheckDepatureCar_Result',
        'CheckSLCar' => 'nav\\CheckSLCar',
        'CheckSLCar_Result' => 'nav\\CheckSLCar_Result',
        'CheckSealNumber' => 'nav\\CheckSealNumber',
        'CheckSealNumber_Result' => 'nav\\CheckSealNumber_Result',
        'CheckDepatureCar1' => 'nav\\CheckDepatureCar1',
        'CheckDepatureCar1_Result' => 'nav\\CheckDepatureCar1_Result',
        'CheckArrivalCar1' => 'nav\\CheckArrivalCar1',
        'CheckArrivalCar1_Result' => 'nav\\CheckArrivalCar1_Result',
        'CheckSLCar1' => 'nav\\CheckSLCar1',
        'CheckSLCar1_Result' => 'nav\\CheckSLCar1_Result',
        'CheckDepatureCar1_test' => 'nav\\CheckDepatureCar1_test',
        'CheckDepatureCar1_test_Result' => 'nav\\CheckDepatureCar1_test_Result',
        'GetWeightLimit' => 'nav\\GetWeightLimit',
        'GetWeightLimit_Result' => 'nav\\GetWeightLimit_Result',
        'GetLoadPlanWeightByAShNumber' => 'nav\\GetLoadPlanWeightByAShNumber',
        'GetLoadPlanWeightByAShNumber_Result' => 'nav\\GetLoadPlanWeightByAShNumber_Result',
        'CheckSLCar2' => 'nav\\CheckSLCar2',
        'CheckSLCar2_Result' => 'nav\\CheckSLCar2_Result',
        'CheckArrivalCar2' => 'nav\\CheckArrivalCar2',
        'CheckArrivalCar2_Result' => 'nav\\CheckArrivalCar2_Result',
        'CheckDepatureCar2' => 'nav\\CheckDepatureCar2',
        'CheckDepatureCar2_Result' => 'nav\\CheckDepatureCar2_Result',
        'CheckDepartureCargoOlymp' => 'nav\\CheckDepartureCargoOlymp',
        'CheckDepartureCargoOlymp_Result' => 'nav\\CheckDepartureCargoOlymp_Result',
        'ArrivalCargoOlymp' => 'nav\\ArrivalCargoOlymp',
        'ArrivalCargoOlymp_Result' => 'nav\\ArrivalCargoOlymp_Result',
        'OpenStageBeforeKPP' => 'nav\\OpenStageBeforeKPP',
        'OpenStageBeforeKPP_Result' => 'nav\\OpenStageBeforeKPP_Result',
    );

    /**
     * @param array $options A array of config values
     * @param string $wsdl The wsdl file to use
     */
    public function __construct($login, $password, $location, $wsdl)
    {
        $this->login = $login;
        $this->password = $password;
        $this->location = $location;
        $options = [];
        foreach (self::$classmap as $key => $value) {
            if (!isset($options['classmap'][$key])) {
                $options['classmap'][$key] = $value;
            }
        }
        $options = array_merge(array(
            'connection_timeout' => 60,
            'features' => 1,
        ), $options);
        parent::__construct($wsdl, $options);
    }


    /**
     * Call a url using curl with ntlm auth
     *
     * @param string $data
     * @param string $action
     * @return string
     * @throws SoapFault on curl connection error
     */
    protected function callCurl($data, $action)
    {
        require_once __DIR__ . '/../Logs.php';
        $logs = \Logs::save('navision', $action, json_encode($data));
        $handle = curl_init();
        $headers = array(
            'Content-type: text/xml; charset="utf-8"',
            'Cache-Control: no-cache',
            'Pragma: no-cache',
            "SOAPAction: $action",
        );
        /** @noinspection CurlSslServerSpoofingInspection */
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_URL, $this->location);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_USERPWD, $this->login . ':' . $this->password);
        curl_setopt($handle, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($handle, CURLOPT_TIMEOUT, 60);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_FAILONERROR, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($handle);
        if (empty($response)) {
            $logs->end(json_encode(
                    ($this->requestRetry < 3 ? 'retry ' : '') . 'error ' .
                    curl_error($handle) . ' ' . curl_errno($handle) . ' ' . $this->requestRetry)
            );
            if ($this->requestRetry < 3) {
                ++$this->requestRetry;
                usleep($this->requestRetry * 500000);
                return $this->callCurl($data, $action);
            }
            throw new SoapFault(curl_error($handle), curl_error($handle));
        }
        curl_close($handle);
        $logs->end(json_encode($response));
        return $response;
    }

    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $this->requestRetry = 0;
        // $location will be override with $this->location
        return $this->callCurl($request, $action);
    }

    /**
     * @param CheckDepatureCar $parameters
     * @return CheckDepatureCar_Result|NavError
     */
    public function CheckDepatureCar(CheckDepatureCar $parameters)
    {
        try {
            return $this->__soapCall('CheckDepatureCar', array($parameters));
        } catch (Exception $e) {
            return new NavError('Ошибка связи с Navision: ' . $e->getMessage());
        }
    }

    /**
     * @param CheckSLCar $parameters
     * @return CheckSLCar_Result|NavError
     */
    public function CheckSLCar(CheckSLCar $parameters)
    {
        try {
            return $this->__soapCall('CheckSLCar', array($parameters));
        } catch (Exception $e) {
            return new NavError('Ошибка связи с Navision: ' . $e->getMessage());
        }
    }

    /**
     * @param CheckSealNumber $parameters
     * @return CheckSealNumber_Result|NavError
     */
    public function CheckSealNumber(CheckSealNumber $parameters)
    {
        try {
            return $this->__soapCall('CheckSealNumber', array($parameters));
        } catch (Exception $e) {
            return new NavError('Ошибка связи с Navision: ' . $e->getMessage());
        }
    }

    /**
     * @param CheckDepatureCar1 $parameters
     * @return CheckDepatureCar1_Result|NavError
     */
    public function CheckDepatureCar1(CheckDepatureCar1 $parameters)
    {
        try {
            return $this->__soapCall('CheckDepatureCar1', array($parameters));
        } catch (Exception $e) {
            return new NavError('Ошибка связи с Navision: ' . $e->getMessage());
        }
    }

    /**
     * @param CheckArrivalCar1 $parameters
     * @return CheckArrivalCar1_Result|NavError
     */
    public function CheckArrivalCar1(CheckArrivalCar1 $parameters)
    {
        try {
            return $this->__soapCall('CheckArrivalCar1', array($parameters));
        } catch (Exception $e) {
            return new NavError('Ошибка связи с Navision: ' . $e->getMessage());
        }
    }

    /**
     * @param CheckSLCar1 $parameters
     * @return CheckSLCar1_Result|NavError
     */
    public function CheckSLCar1(CheckSLCar1 $parameters)
    {
        try {
            return $this->__soapCall('CheckSLCar1', array($parameters));
        } catch (Exception $e) {
            return new NavError('Ошибка связи с Navision: ' . $e->getMessage());
        }
    }

    /**
     * @param CheckDepatureCar1_test $parameters
     * @return CheckDepatureCar1_test_Result|NavError
     */
    public function CheckDepatureCar1_test(CheckDepatureCar1_test $parameters)
    {
        try {
            return $this->__soapCall('CheckDepatureCar1_test', array($parameters));
        } catch (Exception $e) {
            return new NavError('Ошибка связи с Navision: ' . $e->getMessage());
        }
    }

    /**
     * @param GetWeightLimit $parameters
     * @return GetWeightLimit_Result|NavError
     */
    public function GetWeightLimit(GetWeightLimit $parameters)
    {
        try {
            return $this->__soapCall('GetWeightLimit', array($parameters));
        } catch (Exception $e) {
            return new NavError('Ошибка связи с Navision: ' . $e->getMessage());
        }
    }

    /**
     * @param GetLoadPlanWeightByAShNumber $parameters
     * @return GetLoadPlanWeightByAShNumber_Result|NavError
     */
    public function GetLoadPlanWeightByAShNumber(GetLoadPlanWeightByAShNumber $parameters)
    {
        try {
            return $this->__soapCall('GetLoadPlanWeightByAShNumber', array($parameters));
        } catch (Exception $e) {
            return new NavError('Ошибка связи с Navision: ' . $e->getMessage());
        }
    }

    /**
     * @param CheckSLCar2 $parameters
     * @return CheckSLCar2_Result|NavError
     */
    public function CheckSLCar2(CheckSLCar2 $parameters)
    {
        try {
            return $this->__soapCall('CheckSLCar2', array($parameters));
        } catch (Exception $e) {
            return new NavError('Ошибка связи с Navision: ' . $e->getMessage());
        }
    }

    /**
     * @param CheckArrivalCar2 $parameters
     * @return CheckArrivalCar2_Result|NavError
     */
    public function CheckArrivalCar2(CheckArrivalCar2 $parameters)
    {
        try {
            return $this->__soapCall('CheckArrivalCar2', array($parameters));
        } catch (Exception $e) {
            return new NavError('Ошибка связи с Navision: ' . $e->getMessage());
        }
    }

    /**
     * @param CheckDepatureCar2 $parameters
     * @return CheckDepatureCar2_Result|NavError
     */
    public function CheckDepatureCar2(CheckDepatureCar2 $parameters)
    {
        try {
            return $this->__soapCall('CheckDepatureCar2', array($parameters));
        } catch (Exception $e) {
            return new NavError('Ошибка связи с Navision: ' . $e->getMessage());
        }
    }

    /**
     * @param CheckDepartureCargoOlymp $parameters
     * @return CheckDepartureCargoOlymp_Result|NavError
     */
    public function CheckDepartureCargoOlymp(CheckDepartureCargoOlymp $parameters)
    {
        try {
            return $this->__soapCall('CheckDepartureCargoOlymp', array($parameters));
        } catch (Exception $e) {
            return new NavError('Ошибка связи с Navision: ' . $e->getMessage());
        }
    }

    /**
     * @param ArrivalCargoOlymp $parameters
     * @return ArrivalCargoOlymp_Result|NavError
     */
    public function ArrivalCargoOlymp(ArrivalCargoOlymp $parameters)
    {
        try {
            return $this->__soapCall('ArrivalCargoOlymp', array($parameters));
        } catch (Exception $e) {
            return new NavError('Ошибка связи с Navision: ' . $e->getMessage());
        }
    }

    /**
     * @param OpenStageBeforeKPP $parameters
     * @return OpenStageBeforeKPP_Result|NavError
     */
    public function OpenStageBeforeKPP(OpenStageBeforeKPP $parameters)
    {
        try {
            return $this->__soapCall('OpenStageBeforeKPP', array($parameters));
        } catch (Exception $e) {
            return new NavError('Ошибка связи с Navision: ' . $e->getMessage());
        }
    }

}
