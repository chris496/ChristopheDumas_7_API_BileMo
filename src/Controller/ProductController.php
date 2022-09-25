<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('api/products', name: 'product', methods: ['GET'])]
    public function getProductList(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $productList = $productRepository->findAll();
        $jsonProductList = $serializer->serialize($productList, 'json');
        
        return new JsonResponse($jsonProductList, Response::HTTP_OK, [], true);
    }

    #[Route('api/products/{id}', name: 'detailProduct', methods: ['GET'])]
    public function getDetailProduct(Product $product, SerializerInterface $serializer): JsonResponse
    {
            $jsonProduct = $serializer->serialize($product, 'json');

            return new JsonResponse($jsonProduct, Response::HTTP_OK, ['accept' => 'json'], true);
    }
}
