<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

use App\Entity\Hero;

class HeroRestController extends FOSRestController
{
    /**
     * Creates a Hero resource
     * @Rest\Post("/hero")
     * @param Request $request
     * @return View
     */
    public function postHero(Request $request): Response
    {
        $hero = new Hero();
        $entityManager = $this->getDoctrine()->getManager();
        $hero->setName($request->get('name'));
        $hero->setFirstname($request->get('firstname'));
        $hero->setLastname($request->get('lastname'));
        $hero->setBirthdate(new \DateTime($request->get('birthdate')));
        
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($hero);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $view = $this->view($hero, 201, ['Access-Control-Allow-Origin' => '*']);
        $view->setFormat('json'); 
        return $this->handleView($view);
        // In case our POST was a success we need to return a 201 HTTP CREATED response
    }

    /**
     * Retrieves an Hero resource
     * @Rest\Get("/hero/{id}")
     */
    public function getHero(int $id): Reponse
    {
        $hero = $this->getDoctrine()
        ->getRepository('App\Entity\Hero')
        ->find($id);

        $view = $this->view($hero, 200, ['Access-Control-Allow-Origin' => '*']);
        $view->setFormat('json'); 
        return $this->handleView($view);
      
    }

     /**
     * Retrieves all Hero resources
     * @Rest\Get("/hero")
     */
    public function getHeroes(): Response
    {
        $heroes = $this->getDoctrine()
        ->getRepository('App\Entity\Hero')
        ->findAll();

        $view = $this->view($heroes, 200, ['Access-Control-Allow-Origin' => '*']);
        $view->setFormat('json'); 
        return $this->handleView($view);
    }
}
