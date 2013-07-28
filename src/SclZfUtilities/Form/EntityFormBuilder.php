<?php
/**
 * Contains the EntityFormBuilder class.
 *
 * @author Tom Oram
 */
namespace SclZfUtilities\Form;

use Doctrine\ORM\EntityManager;
use SclZfUtilities\Exception\RuntimeException;
use SclZfUtilities\Exception\NoFormEntityMapException;
use SclZfUtilities\Hydrator\Placeholder;
use SclZfUtilities\Mapper\GenericMapperInterface;
use SclZfUtilities\Options\FormBuilderOptionsInterface;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Form\Form;
use Zend\Form\FormElementManager;
use Zend\Http\Request;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * This class constructs a form object from a given annotated entity
 * and also handles the reading back of the form data and saving of
 * the object.
 *
 * @author Tom Oram
 */
class EntityFormBuilder
{
    /**
     * Options.
     *
     * @var FormBuilderOptionsInterface
     */
    protected $options;

    /**
     * The Zend FormElementManager.
     *
     * @var FormElementManager
     */
    protected $elementManager;

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
     * @param FormBuilderOptionsInterface $options
     * @param FormElementManager          $elementManager
     * @param Request                     $request
     * @param HydratorInterface           $hydrator
     * @param GenericMapperInterface      $mapper
     * @param AnnotationBuilder           $builder
     */
    public function __construct(
        FormBuilderOptionsInterface $options,
        FormElementManager $elementManager,
        Request $request,
        HydratorInterface $hydrator,
        AnnotationBuilder $builder = null,
        GenericMapperInterface $mapper = null
    ) {
        $this->options           = $options;
        $this->elementManager    = $elementManager;
        $this->request           = $request;
        $this->hydrator          = $hydrator;
        $this->mapper            = $mapper;
        $this->annotationBuilder = $builder;
    }

    /**
     * Sets the mapper which will be used to fetch & save the entity.
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
     * Set the hydrator and adds a submit button to the form.
     *
     * @param  Form   $form
     * @param  object $entity
     * @param  string $submit
     * @return Form
     */
    public function prepareForm(Form $form, $entity, $submit = null)
    {
        if ($form->getHydrator() === null || $form->getHydrator() instanceof Placeholder) {
            $form->setHydrator($this->hydrator);
        }

        if (null !== $submit) {
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
        }

        $form->bind($entity);

        return $form;
    }

    /**
     * Returns a form build from the provided entity using Zend form annotations.
     *
     * @param  object $entity
     * @param  string $submit
     * @return Form
     */
    public function createForm($entity, $submit = null)
    {
        $entityName = get_class($entity);

        $entityMap = $this->options->getFormEntityMap();

        if (array_key_exists($entityName, $entityMap)) {
            $form = $this->elementManager->get($entityMap[$entityName]);

            return $this->prepareForm($form, $entity, $submit);
        }

        if (null === $this->annotationBuilder) {
            throw new NoFormEntityMapException(__METHOD__);
        }

        $form = $this->annotationBuilder->createForm($entity);

        return $this->prepareForm($form, $entity, $submit);
    }

    /**
     * This method checks if the form is submitted and if it has and it valid
     * the object is saved. This method relies on object & form being pre-bound.
     *
     * @param  object           $entity
     * @param  Form             $form
     * @param  callable         $preSaveCallback
     * @return boolean          True if the entity has been saved
     * @throws RuntimeException When save is called but no mapper is set.
     */
    public function save($entity, Form $form, callable $preSaveCallback = null)
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
            $preSaveCallback($entity);
        }

        $this->mapper->save($entity);

        return true;
    }
}
