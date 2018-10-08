<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Form\ProductType;
use App\Entity\Product;

class ApiController extends AbstractController
{
    /**
     * @Route("api/product/{id}", methods={"GET"})
     */
    public function product_get_one(int $id)
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
     * @Route("api/product", methods={"POST"})
     */
    public function product_create(Request $request) {
        //todo implement it
    }

    /**
    * @Route("api/product/{id}", methods={"POST"})
     */
    public function product_update(int $id, Request $request, ValidatorInterface $validator)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        if(empty($product)) {
            throw $this->createNotFoundException( 'No product found for id ' . $id);
        }


        if(!empty($request->get('name')))
        {
            $product->setName($request->get('name'));
        }

        if(!empty($request->get('description')))
        {
            $product->setDescription($request->get('description'));
        }

        if(!empty($request->get('price')))
        {
            $product->setPrice($request->get('price'));
        }

        if(!empty($request->get('category_id')))
        {
            $product->setCategoryId($request->get('category_id'));
        }

        $errors = $validator->validate($product);
        if(count($errors) > 0) {
            //todo transform $errors to json
            return new Response((string) $errors);
        }

        $this->getDoctrine()->getManager()->flush();
        return new Response(json_encode(array('message' => 'Product was updated.')));
    }

    /**
     * @Route("/api/product", name="api_read", methods={"GET"})
     */
    public function product_get_all()
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
