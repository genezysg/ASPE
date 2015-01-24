<?php

namespace Acme\BlogBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Acme\BlogBundle\Model\CursoInterface;
use Acme\BlogBundle\Form\CursoType;
use Acme\BlogBundle\Exception\InvalidFormException;
use Acme\BlogBundle\Entity\Curso;

class CursoHandler implements CursoHandlerInterface
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
        $curso = $this->createCurso();

        return $this->processForm($curso, $parameters, 'POST');
    }

    public function put(CursoInterface $curso, array $parameters)
    {
        return $this->processForm($curso, $parameters, 'PUT');
    }

    public function patch(CursoInterface $curso, array $parameters)
    {
        return $this->processForm($curso, $parameters, 'PATCH');
    }

    public function delete(Curso $curso)
    {
    	return $this->processDelete($curso);
    }
    
    private function processDelete(Curso $curso)
    {
    	$this->om->remove($curso);
    	$this->om->flush($curso);
    
    	return $curso;
    }

    private function processForm(CursoInterface $curso, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new CursoType(), $curso, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $curso = $form->getData();
            $this->om->persist($curso);
            $this->om->flush($curso);

            return $curso;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createCurso()
    {
        return new $this->entityClass();
    }
}