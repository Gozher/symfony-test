<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/", name="app_product")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $allProducts = $doctrine->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $allProducts,
        ]);
    }

    /**
     * @Route("/create-product", name="create_product")
     */
    public function createProduct(Request $request, ManagerRegistry $doctrine): Response
    {

        $product = new Product();

        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task = $form->getData();
            $task->setCreatedAt(new DateTime('now'));
            $task->setUpdatedAt(new DateTime('now'));

            $entityManager = $doctrine->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_product');
        }

        return $this->renderForm('product/create.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/update-product/{id}", name="update_product")
     */
    public function updateProduct(Request $request, ManagerRegistry $doctrine, int $id): Response
    {

        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {

            throw $this->createNotFoundException('No found');

        } else {

            $form = $this->createForm(ProductFormType::class, $product);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $task = $form->getData();
                $task->setCreatedAt($product->getCreatedAt());
                $task->setUpdatedAt(new DateTime('now'));

                $entityManager->persist($task);
                $entityManager->flush();

                return $this->redirectToRoute('app_product');
            }

        }

        return $this->renderForm('product/update.html.twig', [
            'form' => $form,
        ]);

    }

    /**
     * @Route("/delete-product/{id}", name="delete_product")
     */
    public function deleteProduct(Request $request, ManagerRegistry $doctrine, int $id): Response
    {

        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No found');
        } else {
            $entityManager->remove($product);
            $entityManager->flush();
            return $this->redirectToRoute('app_product');
        }

    }
}
