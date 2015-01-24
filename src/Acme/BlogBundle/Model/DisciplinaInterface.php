<?php
namespace Acme\BlogBundle\Model;

Interface DisciplinaInterface
{
	public function getCodigo();
	
	public function setNome($nome);
	public function getNome();
	
	public function setHoras($horas);
	public function getHoras();
	
}