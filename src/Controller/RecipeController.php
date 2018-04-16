<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Recipe;

class RecipeController extends Controller
{
    /**
     * Matches /recipe/ exactly
     *
     * @Route("/recipe", name="recipe")
     */
    public function index()
    {
        $request = new Request();

        $myRecipe = new Recipe();
        $myRecipe->setName("");
        $myRecipe->setPrice(null);
        $myRecipe->setSumary("");
        $myRecipe->setTags("");

        $form = $this->createFormBuilder($myRecipe)
            ->add("name", TextType::class)
            ->add("price", IntegerType::class)
            ->add("sumary", TextType::class)
            ->add("tags",TextType::class)
            ->add("save", SubmitType::class, array('label'=>"Create recipe"))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $myRecipe = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($myRecipe);
            $entityManager->flush();

            $recipeId = $myRecipe->getId();
            $this->display($recipeId);
        }

        return $this->render('recipe/index.html.twig', array(
            'form'=>$form->createView()
        ));
    }


    /**
     * Matches /recipe/*
     *
     * @Route("/recipe/{id}", name="display")
     */
    public function display($id){
        $myRecipe= $this->getDoctrine()->getManager()->find("Recipe",$id);
        return $this->render("recipe/view.html.twig/".$id,array("recipe"=>$myRecipe));
    }
}