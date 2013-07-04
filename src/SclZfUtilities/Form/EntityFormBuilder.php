<?php
/**
 * Contains the EntityFormBuilder class.
 *
 * @author Tom Oram
 */
namespace SclZfUtilities\Form;

use Doctrine\ORM\EntityManager;
use SclZfUtilities\Exception\RuntimeException;
use SclZfUtilities\Mapper\GenericMapperInterface;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Form\Form;
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
        $form->setHydrator($this->hydrator);

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
