<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Product;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/read", name="api_read")
     */
    public function read()
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findall();

        $jsonProducts = array();
        foreach($products as $product)
        {
            $jsonProducts[] = array(
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice(),
                'category_id' => $product->getCategoryId(),
                'created' => $product->getCreated(),
                'modified' => $product->getModified()
            );
        }

        return new Response(json_encode($jsonProducts));
    }

    /**
     * @Route("api/read/{id}")
     */
    public function read_one(int $id)
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $product = $repository->find($id);

        return new Response(json_encode(array(
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'created' => $product->getCreated(),
            'modified' => $product->getModified()
        )));
    }
}
