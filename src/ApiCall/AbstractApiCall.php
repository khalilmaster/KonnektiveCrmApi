<?php

namespace LE\KonnektiveCrmApi\ApiCall;

use LE\KonnektiveCrmApi\DTO\AbstractKonnektiveDto;
use LE\KonnektiveCrmApi\Exception\MissingFieldException;
use LE\KonnektiveCrmApi\Exception\MissingValueException;
use function var_dump;

/**
 * Class AbstractApiCall
 * @package LE\KonnektiveCrmApi\ApiCall
 */
class AbstractApiCall implements ApiCallInterface
{
    const CALL_NAME = self::CALL_NAME;
    const DTO_CLASS_FQN = self::DTO_CLASS_FQN;
    const API_URI = self::API_URI;
    const REQUEST_METHOD = self::API_URI;

    /**
     * @var AbstractKonnektiveDto
     */
    protected $requestDataDTO;

    /**
     * @var array
     */
    protected $requestDataArray = [];

    /**
     * @var boolean
     */
    protected $resultSuccess = 0;

    /**
     * @var string
     */
    protected $resultMessage = '';

    /**
     * @var string
     */
    protected $resultData = '';

    /**
     * @var string
     */
    protected $errorMessage = '';

    /**
     * @var string
     */
    protected $errorCode = '';

    /**
     * Field names required for api call
     *
     * @var array
     */
    protected $requiredFields = [];

    /**
     * AbstractApiCall constructor.
     * @param AbstractKonnektiveDto|null $requestData
     */
    public function __construct(AbstractKonnektiveDto $requestData = null)
    {
        $this->requestDataDTO = $requestData;
    }

    /**
     * @param array $data
     * @return AbstractKonnektiveDto
     */
    public function createDataObject($data = [])
    {
        $dtoPath = static::DTO_CLASS_FQN;

        return new $dtoPath($data);
    }

    /**
     * Validates api call against api Data Transfer Object
     *
     * @return void
     * @throws MissingFieldException
     * @throws MissingValueException
     *
     */
    public function validate()
    {
        $requestArray = $this->getRequestDataArray();

        foreach ($this->requiredFields as $f) {
            $exception = false;

            if (!array_key_exists($f,$requestArray)) {
                $exception = new MissingFieldException(static::CALL_NAME, $f);
            }

            if ( empty($requestArray[$f])) {
                $exception = new MissingValueException(static::CALL_NAME, $f);
            }

            if ($exception) {

                $this->errorCode = $exception->getCode();
                $this->errorMessage = $exception->getMessage();
                var_dump($exception);
                die('hi');

                throw $exception;
            }
        }


    }

    /**
     * @return AbstractKonnektiveDto
     */
    public function getRequestDataDTO()
    {
        return $this->requestDataDTO;
    }

    /**
     * @param AbstractKonnektiveDto $requestDataDTO
     * @return AbstractApiCall
     */
    public function setRequestDataDTO(AbstractKonnektiveDto $requestDataDTO)
    {
        $this->requestDataDTO = $requestDataDTO;

        return $this;
    }

    /**
     * @return array
     */
    public function getRequestDataArray()
    {
        if (0 === count($this->requestDataArray)) {
            $this->requestDataArray = $this->requestDataDTO->toArray();
        }

        return $this->requestDataArray;
    }


    /**
     * @return bool
     */
    public function isResultSuccess()
    {
        return $this->resultSuccess;
    }

    /**
     * @param bool $resultSuccess
     * @return AbstractApiCall
     */
    public function setResultSuccess($resultSuccess)
    {
        $this->resultSuccess = $resultSuccess;

        return $this;
    }

    /**
     * @return string
     */
    public function getResultMessage()
    {
        return $this->resultMessage;
    }

    /**
     * @param string $resultMessage
     * @return AbstractApiCall
     */
    public function setResultMessage($resultMessage)
    {
        $this->resultMessage = $resultMessage;

        return $this;
    }

    /**
     * Get request result data. Pass AbstractApiCall::const RESULT_FORMAT_JSON|ARRAY|OBJECT.
     *   Defaults to Array.
     *
     * @return string|array|object Request Result
     */
    public function getResultData($format = ApiCallInterface::RESULT_FORMAT_ARRAY)
    {
        switch ($format) {
            case ApiCallInterface::RESULT_FORMAT_ARRAY:
                return $this->resultData;
                break;
            case ApiCallInterface::RESULT_FORMAT_OBJECT:
                return (object)$this->resultData;
                break;
            case ApiCallInterface::RESULT_FORMAT_JSON:
                return json_encode($this->resultData);
        }

        return $this->resultData;
    }

    /**
     * @param string $resultData
     * @return AbstractApiCall
     */
    public function setResultData($resultData)
    {
        $this->resultData = $resultData;

        return $this;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     * @return AbstractApiCall
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param string $errorCode
     * @return AbstractApiCall
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getCallName()
    {
        return static::CALL_NAME;
    }

    /**
     * @return string
     */
    public function getDataObjectClass()
    {
        return static::DTO_CLASS_FQN;
    }

    /**
     * @return string
     */
    public function getApiUri()
    {
        return static::API_URI;
    }

    /**
     * @return string
     */
    public function getRequestMethod()
    {
        return static::REQUEST_METHOD;
    }
}
