<?php

namespace App\Controller;

use App\Form\ProductFormType;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;
use Knp\Component\Pager\PaginatorInterface;

class ProductController extends AbstractController
{
    /** @var ProductService */
    private $productService;

    public function __construct(
        ProductService $productService
    ) {
        $this->productService = $productService;
    }


    /**
     * Overview of products
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {

        $queryResult = $this->productService->productRepository->getProducts();

        $pagination = $paginator->paginate(
            $queryResult, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('product/index.html.twig', [
            'products' => $pagination,
            'archive' => false
        ]);
    }

    /**
     * Overview archived products
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function archived(Request $request, PaginatorInterface $paginator)
    {

        $queryResult = $this->productService->productRepository->getProducts(true);

        $pagination = $paginator->paginate(
            $queryResult, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('product/archive.html.twig', [
            'products' => $pagination,
            'archive' => true
        ]);
    }

    /**
     * Add product
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function add(Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $product = $this->productService->newProduct();
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $type = 'success';
            $message = $translator->trans('Succesfully added');
            $product = $form->getData();

            $result = $this->productService->productRepository->save($product);
            if ($result['error'] !== null) {
                $logger->error('An error occurred: ' . $result['error'][0]);

                $type = 'warning';
                $message = $translator->trans('Product not added');
            }
            $this->addFlash(
                $type,
                $message
            );

            return $this->redirectToRoute('app_products');
        }

        return $this->render('product/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit product
     * @param $id
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function edit($id, Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {

        $product = $this->productService->productRepository->getProduct($id);

        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $type = 'success';
            $message = $translator->trans('Succesfully edited');
            $product = $form->getData();

            $result = $this->productService->productRepository->save($product);
            if ($result['error'] !== null) {
                $logger->error('An error occurred: ' . $result['error'][0]);

                $type = 'warning';
                $message = $translator->trans('Product not edited');
            }
            $this->addFlash(
                $type,
                $message
            );

            return $this->redirectToRoute('app_products');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Archive product
     * @param $id
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function archive($id, Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $type = 'success';
        $message = $translator->trans('Succesfully archived');
        $product = $this->productService->productRepository->getProduct($id);
        if (!is_object($product)) {
            $type = 'warning';
            $message = $translator->trans('Product not found');
        }

        //Archive product
        $result = $this->productService->archiveProduct($product);
        if ($result['error'] !== null) {
            $logger->error('An error occurred: ' . $result['error'][0]);

            $type = 'warning';
            $message = $translator->trans('Product not archived');
        }

        $this->addFlash(
            $type,
            $message
        );
        return $this->redirectToRoute('app_products');
    }

    /**
     * Unarchive product
     * @param $id
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function unarchive($id, Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $type = 'success';
        $message = $translator->trans('Succesfully unarchived');
        $product = $this->productService->productRepository->getProduct($id);
        if (!is_object($product)) {
            $type = 'warning';
            $message = $translator->trans('Product not found');
        }

        //Archive product
        $result = $this->productService->archiveProduct($product, false);
        if ($result['error'] !== null) {
            $logger->error('An error occurred: ' . $result['error'][0]);

            $type = 'warning';
            $message = $translator->trans('Product not unarchived');
        }

        $this->addFlash(
            $type,
            $message
        );
        return $this->redirectToRoute('app_products');
    }

    /**
     * Delete product action
     * @param $id
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete($id, Request $request, TranslatorInterface $translator, LoggerInterface $logger)
    {
        $type = 'success';
        $message = $translator->trans('Succesfully deleted');
        $product = $this->productService->productRepository->getProduct($id);
        if (!is_object($product)) {
            $type = 'warning';
            $message = $translator->trans('Product not found');
        }

        //Delete product
        $result = $this->productService->productRepository->delete($product);
        if ($result['error'] !== null) {
            $logger->error('An error occurred: ' . $result['error'][0]);

            $type = 'warning';
            $message = $translator->trans('Product not deleted');
        }

        $this->addFlash(
            $type,
            $message
        );
        return $this->redirectToRoute('app_products');
    }
}
