<?php
namespace Acme\BlogBundle\Model;

Interface TopicoInterface
{
	public function getCodigo();

	public function setNome($nome);
	public function getNome();	
}