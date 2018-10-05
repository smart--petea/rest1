<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Form\ProductType;
use App\Entity\Product;

class ApiController extends AbstractController
{
    /**
     * @Route("api/read/{id}", methods={"GET"})
     */
    public function get_one(int $id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        if(empty($product))
        {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        return new Response(json_encode(
            array(
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'category_id' => $product->getCategoryId(),
                'created' => $product->getCreated(),
                'modified' => $product->getModified()
            )
        ));
    }

    /**
    * @Route("api/read/{id}", methods={"POST"})
     */
    public function update_one(int $id, Request $request)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        if(empty($product)) {
            throw $this->createNotFoundException( 'No product found for id ' . $id);
        }

        $name = $request->get('name');
        $description = $request->get('description');
        $price = $request->get('price');
        $category_id = $request->get('category_id');

        if(!empty($name))
        {
            $product->setName($name);
        }

        if(!empty($description))
        {
            $product->setDescription($description);
        }

        if(!empty($category_id))
        {
            $product->setCategoryId($category_id);
        }

        if(!empty($price))
        {
            $product->setPrice($price);
        }

        $this->getDoctrine()->getManager()->flush();

        return new Response(json_encode(array('message' => 'Product was updated.')));
    }

    /**
     * @Route("/api/read", name="api_read", methods={"GET"})
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

}
