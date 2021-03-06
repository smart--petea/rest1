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
     * @Route("api/product/{id}", methods={"DELETE"})
     */
    public function product_delete(int $id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        if(empty($product))
        {
            return new Response(
                json_encode(
                    array(
                        'message' => 'The product does not exist.'
                    )
                ),
                404
            );
        }

        $this->getDoctrine()->getManager()->remove($product);
        $this->getDoctrine()->getManager()->flush();

        return new Response(
            json_encode(
                array(
                    'message' => 'Product was deleted.'
                )
            )
        );
    }

    /**
     * @Route("api/product", methods={"POST"})
     */
    public function product_create(Request $request, ValidatorInterface $validator) {
        $product = new Product();

        $product->setName($request->get('name'));
        $product->setDescription($request->get('description'));
        $product->setPrice($request->get('price'));
        $product->setCategoryId($request->get('category_id'));

        $errors = $validator->validate($product);
        if(count($errors) > 0) {
            $errs = array();
            foreach($errors as $err)
            {
                $errs[$err->getPropertyPath()] = $err->getMessage();
            }


            return new Response(
                json_encode(
                    array(
                        'message' => 'Unable to create product.',
                        'errs' => $errs
                    )
                )
            );
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($product);
        $manager->flush();

        return new Response(json_encode(array('message' => "Product was created.")));
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
            $errs = array();
            foreach($errors as $err)
            {
                $errs[$err->getPropertyPath()] = $err->getMessage();
            }

            return new Response(
                json_encode(
                    array(
                        'message' => 'Could not update.',
                        'errs' => $errs
                    )
                )
            );
        }

        $this->getDoctrine()->getManager()->flush();
        return new Response(json_encode(array('message' => 'Product was updated.')));
    }

    /**
     * @Route("/api/product", name="api_read", methods={"GET"})
     */
    public function product_get_all(Request $request)
    {
        $keywords = $request->query->get('keywords');
        $repository = $this->getDoctrine()->getRepository(Product::class);
        if(empty($keywords)) {
            $products = $repository->findall();
        } else {
            $products = $repository
                            ->createQueryBuilder('p')
                            ->where('p.name like :keywords')
                            ->orWhere('p.description like :keywords')
                            ->setParameter('keywords', '%'.$keywords.'%')
                            ->getQuery()
                            ->getResult();
        }

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
