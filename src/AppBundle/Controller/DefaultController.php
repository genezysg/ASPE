<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\Mapping as ORM;

class DefaultController extends Controller {
	/**
	 * @Route("/", name="homepage")
	 */
	public function indexAction() {
		return $this->render ( 'default/index.html.twig' );
	}
	
	/**
	 * @Route("/create/{name}", name="create")
	 */
	public function createAction($name) {
		$product = new Product ();
		$product->setName ( $name );
		$product->setPrice ( "4.50" );
		$product->setDescription ( "Sabao para lavar roupa" );
		
		$em = $this->getDoctrine ()->getManager ();
		
		$em->persist ( $product );
		$em->flush ();
		
		return new Response ( 'Novo produto Criado. Codigo: ' . $product->getId () );
	}
	
	/**
	 * @Route("/fetch/{id}", name="fetch")
	 */
	public function fetchAction($id) {
		$product = new Product ();
		$product = $this->getDoctrine ()->getRepository ( 'AppBundle:Product' )->find ( $id );
		
		if (! $product)
			throw $this->createNotFoundException ( 'Produto nao encontrado: ' . $id );
		else
			return new Response ( 'Produto encontrado:<br> Nome: ' . $product->getName () . '<br>Preço: ' . $product->getPrice () . '<br>Descrição: ' . $product->getDescription () );
	}
	/**	 
	 * @Route("/update/{id}/{nome}", name="update")
	 */
	public function updateAction($id, $nome) {
		$em = $this->getDoctrine()->getManager();
		$product = $em->getRepository('AppBundle:Product')->find($id);
		
		if (!$product)
			throw $this->createNotFoundException ( 'Produto nao encontrado: ' . $id );
		
		if ($nome)
			$product->setName($nome);
		
		$em->flush();
		return new Response ( 'Dados do produto atualizado ');
	}
	/**
	 * @Route("/remove/{id}", name="remove")
	 */
	public function removeAction($id) {
		$em = $this->getDoctrine()->getManager();
		$product = $em->getRepository('AppBundle:Product')->find($id);
		
		if (!$product)
			throw $this->createNotFoundException ( 'Produto nao encontrado: ' . $id );
		
		$em->remove($product);		
		$em->flush();
		
		return new Response ('Produto Removido');
	}
	/**
	 * @Route("/fetch/limitprice/{price}")
	 */
	public function fetchByPriceLimit($price){
		$repository = new Registry();
		$repository = $this->getDoctrine()
			->getRepository('AppBundle:Product');
		
		$query = $repository->createQueryBuilder('p')
			->where('p.price < :price')
			->setParameter('price',$price)
			->orderBy('p.price','ASC')
			->getQuery();
		
		$products = $query->getResult();
	}
}
