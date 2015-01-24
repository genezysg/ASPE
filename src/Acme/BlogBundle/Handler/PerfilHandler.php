<?php
namespace Acme\BlogBundle\Handler;

use Acme\BlogBundle\Model\PerfilInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\BlogBundle\Exception\InvalidFormException;
use Symfony\Component\Form\FormFactoryInterface;
use Acme\BlogBundle\Form\PerfilType;
use Acme\BlogBundle\Entity\Perfil;

class PerfilHandler implements PerfilHandlerInterface{
	
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

	public function get($matricula) 
	{
		return $this->repository->find($matricula);
	}

	public function all($limite = 5, $posicao_inicio = 0) 
	{
		return $this->repository->findBy(array(), null, $limite, $posicao_inicio);
	}
	
	public function post(array $parametros) 
	{
		$perfil = $this->createPerfil();
		return $this->processForm($perfil, $parametros, 'POST');
	}

	public function put(PerfilInterface $perfil, array $parametros)
	{		
		return $this->processForm($perfil, $parametros, 'PUT');
	}

	public function patch(PerfilInterface $perfil, array $parametros)
	{
		return $this->processForm($perfil, $parametros, 'PATCH');
	}

	private function processForm(PerfilInterface $perfil, array $parametros, $metodo = "PUT")
	{
		$form = $this->formFactory->create(new PerfilType(), $perfil, array('method' => $metodo));
		$form->submit($parametros, 'PATCH' !== $metodo);
		if ($form->isValid()) {
	
			$perfil = $form->getData();
			$this->om->persist($perfil);
			$this->om->flush($perfil);
	
			return $perfil;
		}
	
		throw new InvalidFormException('Invalid submitted data', $form);
	}
	
	private function createPerfil()
	{
		return new $this->entityClass();
	}
	
	public function delete(Perfil $perfil)
	{
		return $this->processDelete($perfil);
	}
	
	private function processDelete(Perfil $perfil)
	{
		$this->om->remove($perfil);
		$this->om->flush($perfil);
	
		return $perfil;
	}

}