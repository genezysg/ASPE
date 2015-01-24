<?php

namespace Acme\BlogBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Acme\BlogBundle\Model\DisciplinaInterface;
use Acme\BlogBundle\Form\DisciplinaType;
use Acme\BlogBundle\Exception\InvalidFormException;
use Acme\BlogBundle\Entity\Disciplina;

class DisciplinaHandler implements DisciplinaHandlerInterface
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
        $disciplina = $this->createDisciplina();

        return $this->processForm($disciplina, $parameters, 'POST');
    }

    public function put(DisciplinaInterface $disciplina, array $parameters)
    {
        return $this->processForm($disciplina, $parameters, 'PUT');
    }

    public function patch(DisciplinaInterface $disciplina, array $parameters)
    {
        return $this->processForm($disciplina, $parameters, 'PATCH');
    }

    public function delete(Disciplina $disciplina)
    {
    	return $this->processDelete($disciplina);
    }
    
    private function processDelete(Disciplina $disciplina)
    {
    	$this->om->remove($disciplina);
    	$this->om->flush($disciplina);
    
    	return $disciplina;
    }

    private function processForm(DisciplinaInterface $disciplina, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new DisciplinaType(), $disciplina, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $disciplina = $form->getData();
            $this->om->persist($disciplina);
            $this->om->flush($disciplina);

            return $disciplina;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createDisciplina()
    {
        return new $this->entityClass();
    }
}