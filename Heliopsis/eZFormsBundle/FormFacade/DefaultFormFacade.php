<?php
/**
 * @author: bchoquet
 */

namespace Heliopsis\eZFormsBundle\FormFacade;

use eZ\Publish\API\Repository\Values\Content\Location;
use Heliopsis\eZFormsBundle\Exceptions\BadConfigurationException;
use Heliopsis\eZFormsBundle\Exceptions\UnknownFormException;
use Heliopsis\eZFormsBundle\FormFacade\FormFacadeInterface;
use Heliopsis\eZFormsBundle\FormHandler\NullHandler;
use Heliopsis\eZFormsBundle\Provider\FormProviderInterface;
use Heliopsis\eZFormsBundle\Provider\HandlerProviderInterface;
use Heliopsis\eZFormsBundle\Provider\ResponseProviderInterface;
use Symfony\Component\HttpFoundation\Response;

class DefaultFormFacade implements FormFacadeInterface
{
    /**
     * @var FormProviderInterface
     */
    protected $formProvider;

    /**
     * @var HandlerProviderInterface
     */
    protected $handlerProvider;

    /**
     * @var ResponseProviderInterface
     */
    protected $responseProvider;

    /**
     * @param FormProviderInterface $formProvider
     * @param HandlerProviderInterface $handlerProvider
     */
    function __construct(FormProviderInterface $formProvider = null, HandlerProviderInterface $handlerProvider = null, ResponseProviderInterface $responseProvider = null )
    {
        $this->formProvider = $formProvider;
        $this->handlerProvider = $handlerProvider;
        $this->responseProvider = $responseProvider;
    }

    /**
     * @param \Heliopsis\eZFormsBundle\Provider\FormProviderInterface $formProvider
     * @return void
     */
    public function setFormProvider(FormProviderInterface $formProvider)
    {
        $this->formProvider = $formProvider;
    }

    /**
     * @param \Heliopsis\eZFormsBundle\Provider\HandlerProviderInterface $handlerProvider
     * @return void
     */
    public function setHandlerProvider(HandlerProviderInterface $handlerProvider)
    {
        $this->handlerProvider = $handlerProvider;
    }

    /**
     * @param \Heliopsis\eZFormsBundle\Provider\ResponseProviderInterface $responseProvider
     * @return DefaultFormFacade
     */
    public function setResponseProvider(ResponseProviderInterface $responseProvider)
    {
        $this->responseProvider = $responseProvider;
    }

    /**
     * Renvoie le formulaire symfony correspondant au contenu eZPublish
     * @param Location $location
     * @return \Symfony\Component\Form\FormInterface
     * @throws \Heliopsis\eZFormsBundle\Exceptions\UnknownFormException si aucun formulaire ne correspond
     */
    public function getForm(Location $location)
    {
        if( null === $this->formProvider )
        {
            throw new UnknownFormException();
        }

        return $this->formProvider->getForm( $location );
    }

    /**
     * Renvoie le handler de formulaire correspondant au contenu eZPublish
     * @param Location $location
     * @return \Heliopsis\eZFormsBundle\FormHandler\FormHandlerInterface
     */
    public function getHandler(Location $location)
    {
        if( null === $this->handlerProvider )
        {
            return new NullHandler();
        }

        return $this->handlerProvider->getHandler( $location );
    }

    /**
     * @param Location $location
     * @parma mixed $data
     * @return Response
     */
    public function getResponse(Location $location, $data)
    {
        if( null === $this->responseProvider )
        {
            throw new BadConfigurationException( "No Response Provider set in default FormFacade" );
        }

        return $this->responseProvider->getResponse( $location, $data );
    }


}