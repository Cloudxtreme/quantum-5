<?php

/**
 * Portal controller.
 *
 * @package Quantum
 * @subpackage Controller
 * @author Gabor Klausz
 */
class PortalController extends PageController
{
	/**
	 * PreDispatch.
	 *
	 * @return void
	 */
	public function preDispatch($actionName)
	{
		parent::preDispatch($actionName);
	}

	/**
	 * PostDispatch.
	 *
	 * @params $page string   Meghatarozza a Layout templatet.
	 *
	 * @return void
	 */
	public function postDispatch($templateFileName, $page)
	{
		parent::postDispatch($templateFileName, $page);
	}

	/**
	 * 404-es oldal
	 *
	 * @return void
	 */
	public function do404()
	{
	}
}