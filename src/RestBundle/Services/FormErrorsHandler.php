<?php
namespace RestBundle\Services;

use JMS\Serializer\Handler\FormErrorHandler;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Naming\CamelCaseNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use Symfony\Component\Form\Form;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class FormErrorsHandler
 * @package RestBundle\Services
 */
class FormErrorsHandler
{
    /** @var TranslatorInterface $translator */
    protected $translator;

    /**
     * FormErrorsHandler constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Form $form
     * @return array
     */
    public function getErrors(Form $form)
    {
        $handler = new FormErrorHandler($this->translator);
        $visitor = new JsonSerializationVisitor(new SerializedNameAnnotationStrategy(new CamelCaseNamingStrategy()));
        $errors = $handler->serializeFormToJson($visitor, $form, array());

        /** @var array $errors_copy */
        $errors_copy = $errors->getArrayCopy()['children'];
        $errors_as_array = [];

        foreach ($errors_copy as $field => $error) {
            if (isset($error->getArrayCopy()['errors'])) {
                $errors_as_array[$field] = $error->getArrayCopy()['errors'][0];
            }
        }

        return $errors_as_array;
    }

    /**
     * getStringErrors
     *
     * @param Form $form
     *
     * @return string
     */
    public function getStringErrors(Form $form)
    {
        $errors = $this->getErrors($form);
        $stringErrors = "";

        foreach ($errors as $field => $error) {
            $stringErrors.= $field . ": " . $error;
        }

        return $stringErrors;
    }
}