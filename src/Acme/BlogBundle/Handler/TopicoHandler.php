<?php

namespace Acme\BlogBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Acme\BlogBundle\Model\TopicoInterface;
use Acme\BlogBundle\Form\TopicoType;
use Acme\BlogBundle\Exception\InvalidFormException;
use Acme\BlogBundle\Entity\Topico;

class TopicoHandler implements TopicoHandlerInterface
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
        $topico = $this->createTopico();

        return $this->processForm($topico, $parameters, 'POST');
    }

    public function put(TopicoInterface $topico, array $parameters)
    {
        return $this->processForm($topico, $parameters, 'PUT');
    }

    public function patch(TopicoInterface $topico, array $parameters)
    {
        return $this->processForm($topico, $parameters, 'PATCH');
    }

    public function delete(Topico $topico)
    {
    	return $this->processDelete($topico);
    }
    
    private function processDelete(Topico $topico)
    {
    	$this->om->remove($topico);
    	$this->om->flush($topico);
    
    	return $topico;
    }

    private function processForm(TopicoInterface $topico, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new TopicoType(), $topico, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $topico = $form->getData();
            $this->om->persist($topico);
            $this->om->flush($topico);

            return $topico;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createTopico()
    {
        return new $this->entityClass();
    }
}