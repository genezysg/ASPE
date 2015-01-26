<?php
namespace Acme\BlogBundle\Model;

Interface EmentaInterface
{
	public function getCodigo();
	
	public function getDisciplina();
	public function setDisciplina($cod_disciplina);
	
	public function getTopico();
	public function setTopico($topico);
	
	public function getIndice();
	public function setIndice($indice);	
}