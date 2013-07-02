<?php
/**
 * Contains the EntityFormBuilder class.
 *
 * @author Tom Oram
 */
namespace SclZfUtilities\Form;

use Doctrine\ORM\EntityManager;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use SclZfUtilities\Mapper\GenericMapperInterface;
use Zend\Form\Form;
use Zend\Http\Request;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * This class constructs a form object from a given annotated object
 * and also handles the reading back of the form data and saving of
 * the object.
 *
 * @author Tom Oram
 */
class EntityFormBuilder
{
    /**
     * The mapper for loading and persisting the entities.
     *
     * @var GenericMapperInterface
     */
    protected $mapper;

    /**
     * The current Request object
     *
     * @var Request
     */
    protected $request;

    /**
     * The builder which constructs forms from annotated entities.
     *
     * @var AnnotationBuilder
     */
    protected $annotationBuilder;

    /**
     * The hydrator to use for form hydration.
     *
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * Initialises the form builder with the objects it needs.
     *
     * @param Request                $request
     * @param AnnotationBuilder      $builder
     * @param HydratorInterface      $hydrator
     * @param GenericMapperInterface $mapper
     */
    public function __construct(
        Request $request,
        AnnotationBuilder $builder,
        HydratorInterface $hydrator,
        GenericMapperInterface $mapper = null
    ) {
        $this->request           = $request;
        $this->annotationBuilder = $builder;
        $this->hydrator          = $hydrator;
        $this->mapper            = $mapper;
    }

    /**
     * Sets the object manager which will be used to fetch & save the object.
     *
     * @param  GenericMapperInterface $mapper
     * @return self
     */
    public function setMapper(GenericMapperInterface $mapper)
    {
        $this->mapper = $mapper;

        return $this;
    }

    /**
     * This connects up the hydrators for sub-forms to the EntityManager
     * and binds to the object. It also adds a submit button to the form.
     *
     * @param  Form   $form
     * @param  object $object
     * @param  string $submit
     * @return Form
     */
    public function prepareForm(Form $form, $object, $submit)
    {

        $form->setHydrator($this->hydrator);

        $form->add(
            array(
                'name' => 'submit',
                'attributes' => array(
                    'type'  => 'submit',
                    'value' => $submit,
                    'id'    => 'submitbutton',
                ),
            )
        );

        $form->bind($object);

        return $form;
    }

    /**
     * Returns a form build from the provided object using Zend form annotations.
     *
     * @param  object $object
     * @param  string $submit
     * @return Form
     */
    public function getForm($object, $submit)
    {
        $form = $this->annotationBuilder->createForm($object);

        return $this->prepareForm($form, $object, $submit);
    }

    /**
     * This method checks if the form is submitted and if it has and it valid
     * the object is saved. This method relies on object & form being pre-bound.
     *
     * @param  object           $object
     * @param  Form             $form
     * @param  callable         $preSaveCallback
     * @return boolean          True if the object has been saved
     * @throws RuntimeException When save is called but no mapper is set.
     */
    public function saveObject($object, Form $form, $preSaveCallback = null)
    {
        if (!$this->request->isPost()) {
            return false;
        }

        $form->setData($this->request->getPost());

        if (!$form->isValid()) {
            return false;
        }

        if (!$this->mapper instanceof GenericMapperInterface) {
            throw new RuntimeException('No mapper set in ' . __METHOD__);
        }

        if (null !== $preSaveCallback) {
            $preSaveCallback($object);
        }

        $this->mapper->save($object);

        return true;
    }
}
