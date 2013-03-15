<?php
/**
 * Constains the FormSubmitted plugin class definition.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
namespace SclZfUtilities\Controller\Plugin;

use Zend\Form\Form;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * A plugin which performs basic checking of whether a form has been sumitted
 * and validates its data.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class FormSubmitted extends AbstractPlugin
{
    /**
     * Return the Request object.
     *
     * @return \Zend\Http\Request
     */
    private function getRequest()
    {
        $controller = $this->getController();
        return $controller->getRequest();
    }

    /**
     * Check is this request is a post, if it is the data is added to the form
     * and the form is then validated.
     *
     * @param Form $form
     * @return boolean
     */
    public function __invoke(Form $form)
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return false;
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            $this->getController()
                ->flashMessenger()
                ->addErrorMessage('There were errors.');

            return false;
        }

        return true;
    }
}
