<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Doctrine\Common\Persistence\ObjectManager;



class ProduitController extends AbstractController
{

	/**
     * @Route("/", name="acceuil")
     */
 public function acceuil()
 	{
 		return $this->render('acceuil/acceuil.html.twig');
 	}


    /**
     * @Route("/produit", name="produit")
     */
    public function index(Request $request)
    {
    	$manager = $this->getDoctrine()->getManager();
    	$produit = new Produit();
    	$form    = $this->createForm(ProduitType::class, $produit);
    	$form->handleRequest($request);
    	if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid())
    	{
    		$manager->persist($produit);
    		$manager->flush();

    		 return $this->redirectToRoute('acceuil');
    	}
        return $this->render('produit/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/liste/", name="liste")
     */
     public function Liste()
     {
     	 
    	$repo= $this->getDoctrine()->getRepository(Produit::class)->findAll();
    	if (!$repo) 
    	{
    		throw $this->createNotFoundException("Pas de produit dans la base");
    	}

		 return $this->render('liste/index.html.twig' , [

		 		'produit'  => $repo,
		 ]);

     }
     /**
     * @Route("/delete/{id}", name="produit_delete")
     */

     public function delete(Produit $produit)
     {

     		$manager = $this->getDoctrine()->getManager();
     		$manager->remove($produit);
     		$manager->flush();

            return $this->redirectToRoute('liste');
     		return new Response ('Produit Supprimé');

     }


      /**
     * @Route("/edit/{id}", name="produit_edit")
     */

     public function edit(Request $request, Produit $produit )
     {
     		$manager = $this->getDoctrine()->getManager();
    	
    	$form    = $this->createForm(ProduitType::class, $produit);
    	$form->handleRequest($request);
    	if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid())
    	{
    		//$manager->persist($produit);
    		$manager->flush();
    		 return $this->redirectToRoute('liste');
    	}
        return $this->render('produit/index.html.twig', [
            'form' => $form->createView(),
        ]);

     }
     
}
