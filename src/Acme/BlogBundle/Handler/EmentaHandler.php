<?php

namespace Acme\BlogBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Acme\BlogBundle\Model\EmentaInterface;
use Acme\BlogBundle\Form\EmentaType;
use Acme\BlogBundle\Exception\InvalidFormException;
use Acme\BlogBundle\Entity\Ementa;

class EmentaHandler implements EmentaHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }

    public function get($codigo)
    {
        return $this->repository->find($codigo);
    }
    
    public function all($limit = 0, $offset = 0)
    {
        return $this->repository->findBy(array(), null, $limit, $offset);
    }

    public function post(array $parameters)
    {
        $ementa = $this->createEmenta();

        return $this->processForm($ementa, $parameters, 'POST');
    }

    public function put(EmentaInterface $ementa, array $parameters)
    {
        return $this->processForm($ementa, $parameters, 'PUT');
    }

    public function patch(EmentaInterface $ementa, array $parameters)
    {
        return $this->processForm($ementa, $parameters, 'PATCH');
    }

    public function delete(Ementa $ementa)
    {
    	return $this->processDelete($ementa);
    }
    
    private function processDelete(Ementa $ementa)
    {
    	$this->om->remove($ementa);
    	$this->om->flush($ementa);
    
    	return $ementa;
    }

    private function processForm(EmentaInterface $ementa, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new EmentaType(), $ementa, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $ementa = $form->getData();
            $this->om->persist($ementa);
            $this->om->flush($ementa);

            return $ementa;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createEmenta()
    {
        return new $this->entityClass();
    }
}