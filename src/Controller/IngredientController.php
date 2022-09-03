<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request as BrowserKitRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'ingredient.index')]
    public function index(IngredientRepository $ingredientRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $ingredients = $paginator->paginate(
            $ingredientRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/ingredient/index.html.twig', [
            "ingredients" => $ingredients
        ]);
    }

    #[Route('/ingredients/nouveau', name: 'ingredient.add')]
    public function add(Request $request, IngredientRepository $ingredientRepository): Response 
    {
        $form = $this->createForm(IngredientType::class, new Ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

           $ingredientRepository->add($ingredient, true);

            $this->addFlash(
                'success',
                'votre ingrédient a été créée avec succès'
            );

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/ingredients/modifier/{id}', name: 'ingredient.edit')]
    public function edit($id, IngredientRepository $ingredientRepository, Request $request): Response
    {
        $ingredient = $ingredientRepository->find($id);
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            $ingredientRepository->add($ingredient, true);

            $this->addFlash(
                'success',
                'votre ingrédient a été modifié avec succès'
            );

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/ingredients/suppression/{id}', name: 'ingredient.remove')]
    public function remove($id, IngredientRepository $ingredientRepository): Response 
    {
        $ingredient = $ingredientRepository->find($id);

        $ingredientRepository->remove($ingredient, true);


        $this->addFlash(
            'success',
            'votre ingrédient a été supprimé avec succès'
        );

        return $this->redirectToRoute('ingredient.index');
    }
}
