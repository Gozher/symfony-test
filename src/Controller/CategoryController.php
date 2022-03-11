<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryRegisterType;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{


    /**
     * @Route("/category", name="app_category")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        

        $allCategory = $doctrine->getRepository(Category::class)->findAll();

        return $this->render('category/index.html.twig', [
            'allCategories' => $allCategory,
        ]);
    }

    /**
     * @Route("/category-create", name="create_category")
     */
    public function createCategory(Request $request, ManagerRegistry $doctrine): Response
    {

        $category = new Category();

        $form = $this->createForm(CategoryRegisterType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task = $form->getData();
            $task->setCreatedAt(new DateTime('now'));
            $task->setUpdatedAt(new DateTime('now'));

            $entityManager = $doctrine->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_category');
        }

        return $this->renderForm('category/create.html.twig', [
            'form' => $form,
        ]);

    }

    /**
     * @Route("/category-update/{id}", name="update_category")
     */

    public function updateCategory(Request $request, ManagerRegistry $doctrine, int $id): Response
    {

        $entityManager = $doctrine->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);

        if (!$category) {

            throw $this->createNotFoundException('No found');

        } else {

            $form = $this->createForm(CategoryRegisterType::class, $category);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $task = $form->getData();
                $task->setCreatedAt($category->getCreatedAt());
                $task->setUpdatedAt(new DateTime('now'));

                $entityManager->persist($task);
                $entityManager->flush();

                return $this->redirectToRoute('app_category');
            }

        }

        return $this->renderForm('category/update.html.twig', [
            'form' => $form,
        ]);

    }

    /**
     * @Route("/category-delete/{id}", name="delete_category")
     */
    public function deleteCategory(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException('No found');
        }else{
            $entityManager->remove($category);
            $entityManager->flush();
            return $this->redirectToRoute('app_category');
        }
    
    }
}
