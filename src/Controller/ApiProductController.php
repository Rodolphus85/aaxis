<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Type\ListProductFormType;
use App\Form\Type\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/v1/products")
 */
class ApiProductController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ProductRepository $productRepository;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $this->entityManager->getRepository(Product::class);
        $this->validator = $validator;
    }
    
    /**
    * @Route("/list", methods={"GET"})
    */
    public function listAction(Request $request, SerializerInterface $serializer)
    {
        $products = $this->productRepository->findAll();
        $data = $serializer->serialize(
            $products,
            'json',
            ['groups' => ['product']]
        );
        
        return new Response($data); 
    }

    /**
    * @Route("/load", methods={"POST"})
    */
    public function getAction(Request $request, SerializerInterface $serializer)
    {
        $dataSkuProducts = json_decode($request->getContent(), true);

        if (empty($dataSkuProducts['productSkuList'])) {
            return new JsonResponse([
                'success' => false,
                'message'=> 'No products to load.'
            ]);
        }

        try {
            foreach ($dataSkuProducts['productSkuList'] as $dataSkuProduct) {
                $skuList[] = $dataSkuProduct['sku'];
            }

            $products = $this->productRepository->findBy(['sku' => $skuList]);
            
            if (empty($products)) {
                return new JsonResponse([
                    'success' => false,
                    'message'=> 'Sku dont exist.'
                ]);               
            }

            $data = $serializer->serialize(
                $products,
                'json',
                ['groups' => ['product']]
            );
            
            return new Response($data); 

        } catch(\Exception $e) {

            return new JsonResponse([
                'success' => false,
                'message'=> 'Load fail.'
            ]);
        }
    }

    /**
    * @Route("/update", methods={"POST"})
    */
    public function updateAction(Request $request)
    {
        $dataProducts = json_decode($request->getContent(), true);

        if (empty($dataProducts['productList'])) {
            return new JsonResponse([
                'success' => false,
                'message'=> 'No products to update.'
            ]);
        }

        try {
           foreach ($dataProducts['productList'] as $dataProduct) {
                $sku = $dataProduct['sku'];
                $product = $this->productRepository->findOneBy(['sku' => $sku]);

                if (null !== $product) {
                    $product->setProductName($dataProduct['productName']);
                    $product->setDescription($dataProduct['description']);

                    $errorsValidation = $this->validator->validate($product);

                    $messageError = [];
                    foreach ($errorsValidation as $error) {
                        $messageError[] = $error->getPropertyPath() . ': ' .$error->getMessage();
                    }

                    if (!empty($messageError)) {
                        $productsError['sku'] = $sku;
                        $productsError['messages'] = $messageError;

                        $errors['errors'][] =  $productsError;
                    }
                    $this->entityManager->persist($product);
                } else {
                    $productsError['sku'] = $sku;
                    $productsError['messages'] = 'Sku dont exist.';
                    $errors['errors'][] = $productsError;
                }
            }

            if (!empty($errors)) {
                return new JsonResponse([
                    'success' => false,
                    'data'=> $errors
                ]);
            }
            $this->entityManager->flush();

        } catch(\Exception $e) {

            return new JsonResponse([
                'success' => false,
                'message'=> 'Update fail.'
            ]);
        }
        
        return new JsonResponse([
            'success' => true,
            'message'=> 'Products updated!'
        ]);
    }
}