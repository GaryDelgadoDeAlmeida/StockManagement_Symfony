<?php

namespace App\Controller;

use App\Manager\MediaManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\{ Media, Order, Entity, Product, Customer };
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\{ EntityType, ProductType, CustomerType, SearchType };


/**
 * @IsGranted("ROLE_ADMIN")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AdminController extends AbstractController
{
    private $em;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="app_admin")
     */
    public function index(): Response
    {
        $productRepo = $this->em->getRepository(Product::class);

        return $this->render('admin/home.html.twig', [
            "nbrCustomers" => $this->em->getRepository(Customer::class)->countCustomers(),
            "nbrProviders" => $this->em->getRepository(Entity::class)->countEntities(),
            "nbrProducts" => $productRepo->countProducts(),
            "nbrOrders" => $this->em->getRepository(Order::class)->countOrders(),
            "lowStorageProduct" => $productRepo->getLowStorageProduct(1, 10),
            "currentTime" => new \DateTimeImmutable()
        ]);
    }

    /**
     * @Route("/product", name="app_admin_product")
     */
    public function admin_product(Request $request): Response
    {
        $nbrOffset = 0;
        $products = [];

        $limit = 20;
        $offset = $request->get("offset") ? $request->get("offset") : 1;
        $productRepo = $this->em->getRepository(Product::class);
        $searchForm = $this->createForm(SearchType::class, null);
        $searchForm->handleRequest($request);

        if($searchForm->isSubmitted() && $searchForm->isValid()) {
            $researchedValue = $searchForm->getData()["researchedValue"];
            $products = $productRepo->searchProduct($researchedValue);
        } else {
            $products = $productRepo->getProducts($offset, $limit);
            $nbrOffset = ceil($productRepo->countProducts() / $limit);
        }

        return $this->render("admin/product/list.html.twig", [
            "searchForm" => $searchForm->createView(),
            "products" => $products,
            "offset" => $offset,
            "nbrOffset" => $nbrOffset
        ]);
    }

    /**
     * @Route("/product/new", name="app_admin_new_product")
     */
    public function admin_new_product(Request $request, MediaManager $mediaManager): Response
    {
        $response = [];
        $product = new Product();
        $productForm = $this->createForm(ProductType::class, $product);
        $productForm->handleRequest($request);

        // If the form has been submitted and valid
        if($productForm->isSubmitted() && $productForm->isValid()) {
            $currentTime = new \DateTimeImmutable();
            
            try {
                $product->setCreatedAt($currentTime);

                $media = $mediaManager->copyFile(new Media(), $productForm["imgFile"]->getData());
                $media->setProduct($product);
                $media->setCreatedAt($currentTime);

                $this->em->persist($product);
                $this->em->persist($media);
                $this->em->flush();

                $response = [
                    "class" => "success",
                    "message" => "The product ${$product->getName()} has been successfully added."
                ];
            } catch(\Exception $e) {
                $response = [
                    "class" => "danger",
                    "message" => $e->getMessage()
                ];
            } finally {}
        }

        return $this->render("admin/product/form.html.twig", [
            "productForm" => $productForm->createView(),
            "response" => $response
        ]);
    }

    /**
     * @Route("/product/lowstorage", name="app_admin_lowstorage_product")
     */
    public function admin_low_storage_product(Request $request)
    {
        $limit = 20;
        $offset = $request->get("offset") ? $request->get("offset") : 1;
        $productRepo = $this->em->getRepository(Product::class);

        return $this->render("admin/product/lowstorage.html.twig", [
            "lowStorages" => $productRepo->getLowStorageProduct($offset, $limit),
            "offset" => $offset,
            "nbrOffset" => ceil($productRepo->countLowStorageProduct() / $limit)
        ]);
    }

    /**
     * @Route("/product/{productID}", name="app_admin_single_product")
     */
    public function admin_single_product(int $productID): Response
    {
        $product = $this->em->getRepository(Product::class)->find($productID);
        if(empty($product)) {
            return $this->redirectToRoute("app_admin_product");
        }

        $file = [];
        foreach($product->getMedia() as $media) {
            $file = $media;
        }

        return $this->render("admin/product/single.html.twig", [
            "product" => $product,
            "imgFile" => !empty($file) ? $file->getPath() : null
        ]);
    }

    /**
     * @Route("/product/{productID}/update", name="app_update_product")
     */
    public function admin_update_product(int $productID, Request $request): Response
    {
        $product = $this->em->getRepository(Product::class)->find($productID);
        if(empty($product)) {
            return $this->redirectToRoute("app_admin_product");
        }

        $productForm = $this->createForm(ProductType::class, $product);
        $productForm->handleRequest($request);

        // Check if the form has been submitted
        if($productForm->isSubmitted() && $productForm->isValid()) {
            $product->setCreatedAt(new \DateTimeImmutable());

            try {
                // 
            } catch(\Exception $e) {
                // 
            }
        }

        return $this->render("admin/product/form.html.twig", [
            "productForm" => $productForm->createView()
        ]);
    }

    /**
     * @Route("/product/{productID}/remove", name="app_admin_remove_product")
     */
    public function admin_remove_product(int $productID): Response
    {
        return $this->render("admin/product/list.html.twig");
    }

    /**
     * @Route("/sales", name="app_admin_sales")
     */
    public function admin_sales(Request $request): Response
    {
        $limit = 20;
        $offset = $request->get("offset") ? $request->get("offset") : 1;

        return $this->render("admin/order/list.html.twig", [
            "nbrTotalOrders" => 1896,
            "nbrOngoingOrders" => 189,
            "totalAmountSoldedProducts" => 14,
            "ongoingSales" => $this->em->getRepository(Order::class)->getOrders($offset, $limit),
            "bestSelledProducts" => [],
            "bestSeller" => []
        ]);
    }

    /**
     * @Route("/sales/{salesID}", requirements={"salesID" = "^\d+(?:\d+)?$"}, name="app_admin_single_order")
     */
    public function admin_single_sale(int $salesID): Response
    {
        $order = $this->em->getRepository(Order::class)->find($salesID);
        if(empty($order)) {
            return $this->redirectToRoute("app_admin_sales");
        }

        return $this->render("admin/order/single.html.twig", [
            "order" => $order
        ]);
    }

    /**
     * @Route("/sales/ongoing-orders", name="app_admin_ongoing_orders")
     */
    public function admin_ongoing_orders(Request $request): Response
    {
        return $this->render("admin/order/ongoing.html.twig");
    }

    /**
     * @Route("/customer", name="app_admin_customer")
     */
    public function admin_customer(Request $request): Response
    {
        $nbrOffset = 0;
        $customers = [];

        $limit = 20;
        $offset = $request->get("offset") ? $request->get("offset") : 1;
        $customerRepo = $this->em->getRepository(Customer::class);
        $searchForm = $this->createForm(SearchType::class, null);
        $searchForm->handleRequest($request);

        if($searchForm->isSubmitted() && $searchForm->isValid()) {
            $researchedValue = $searchForm->getData()["researchedValue"];
            
            if(!empty($researchedValue)) {
                $customers = $customerRepo->searchCustomers($researchedValue);
                $nbrOffset = ceil($customerRepo->countSearchedCustomer($researchedValue) / $limit);
            } else {
                $customers = $customerRepo->getCustomers($offset, $limit);
                $nbrOffset = ceil($customerRepo->countCustomers() / $limit);
            }
        } else {
            $customers = $customerRepo->getCustomers($offset, $limit);
            $nbrOffset = ceil($customerRepo->countCustomers() / $limit);
        }

        return $this->render("admin/customer/list.html.twig", [
            "offset" => $offset,
            "searchForm" => $searchForm->createView(),
            "nbrOffset" => $nbrOffset,
            "customers" => $customers
        ]);
    }

    /**
     * @Route("/customer/{customerID}", requirements={"customerID" = "^\d+(?:\d+)?$"}, name="app_admin_single_customer")
     */
    public function admin_single_customer(int $customerID, Request $request): Response
    {
        $response = $orders = $pastOrders = [];
        $customerForm = null;
        $onglet = $request->get("onglet") ? $request->get("onglet") : "profile";

        $customer = $this->em->getRepository(Customer::class)->find($customerID);
        if(empty($customer)) {
            return $this->redirectToRoute("app_admin_customer");
        }

        if($onglet === "orders") {
            $orders = $this->em->getRepository(Order::class)->getOrdersByCustomerID($customerID);
        } elseif($onglet === "past-orders") {
            $pastOrders = $this->em->getRepository(Order::class)->getPastOrdersByCustomerID($customerID);
        } else {
            $customerForm = $this->createForm(CustomerType::class, $customer);
            $customerForm->handleRequest($request);

            if($customerForm->isSubmitted() && $customerForm->isValid()) {
                try {
                    // 
                } catch(\Exception $e) {
                    $response = [
                        "class" => "danger",
                        "message" => $e->getMessage()
                    ];
                } finally {}
            }
        }

        return $this->render("admin/customer/single.html.twig", [
            "response" => $response,
            "onglet" => $onglet,
            "customer" => $customer,
            "orders" => $orders,
            "pastOrders" => $pastOrders,
            "customerForm" => !empty($customerForm) ? $customerForm->createView() : null
        ]);
    }

    /**
     * @Route("/customer/{customerID}/order/{orderID}", requirements={"customerID" = "^\d+(?:\d+)?$", "orderID" = "^\d+(?:\d+)?$"}, name="app_admin_customer_single_order")
     */
    public function admin_customer_single_order(int $customerID, int $orderID): Response
    {
        return $this->render("admin/customer/singleOrder.html.twig");
    }

    /**
     * @Route("/provider", name="app_admin_provider")
     */
    public function admin_provider(Request $request): Response
    {
        $limit = 20;
        $offset = $request->get("offset") ? $request->get("offset") : 1;

        return $this->render("admin/entity/list.html.twig", [
            "offset" => $offset,
            "nbrOffset" => ceil($this->em->getRepository(Entity::class)->countEntities() / $limit),
            "entities" => $this->em->getRepository(Entity::class)->getEntities($offset, $limit)
        ]);
    }

    /**
     * @Route("/provider/new", name="app_new_provider")
     */
    public function admin_new_provider(): Response
    {
        return $this->render("admin/entity/form.html.twig");
    }

    /**
     * @Route("/provider/{providerID}", requirements={"providerID" = "^\d+(?:\d+)?$"}, name="app_admin_single_provider")
     */
    public function admin_single_provider(int $providerID, Request $request): Response
    {
        $response = $products = [];
        $entityForm = null;
        $limit = 20;
        $nbrOffset = 0;
        $onglet = $request->get("onglet") ? $request->get("onglet") : "profile";
        $offset = $request->get("offset") ? $request->get("offset") : 1;
        $entityRepo = $this->em->getRepository(Entity::class);
        
        $provider = $entityRepo->find($providerID);
        if(empty($provider)) {
            return $this->redirectToRoute("app_admin_provider");
        }

        if($onglet === "products") {
            $productRepo = $this->em->getRepository(Product::class);
            $products = $productRepo->getProductsByEntityID($providerID, $offset, $limit);
            $nbrOffset = ceil($productRepo->countProductsByEntityID($providerID));
        } elseif($onglet === "profile") {
            $entityForm = $this->createForm(EntityType::class, $provider);
            $entityForm->handleRequest($request);

            if($entityForm->isSubmitted() && $entityForm->isValid()) {
                try {
                    // Save all changes of the provider
                    $entityRepo->add($provider, true);

                    // Send a response to the user if everything worked fine
                    $response = [
                        "class" => "success",
                        "message" => "The provider {$provider->getName()} has been successfully updated"
                    ];
                } catch(\Exception $e) {
                    $response = [
                        "class" => "danger",
                        "message" => $e->getMessage()
                    ];
                } finally {}
            }
        }

        return $this->render("admin/entity/single.html.twig", [
            "onglet" => $onglet,
            "offset" => $offset,
            "provider" => $provider,
            "products" => $products,
            "nbrOffset" => $nbrOffset,
            "response" => $response,
            "entityForm" => !empty($entityForm) ? $entityForm->createView() : null
        ]);
    }

    /**
     * @Route("/employee", name="app_admin_employee")
     */
    public function admin_employee(): Response
    {
        return $this->render("admin/employee/list.html.twig");
    }

    /**
     * @Route("/payment", name="app_admin_payment")
     */
    public function admin_payment(): Response
    {
        return $this->render("admin/payment/index.html.twig");
    }
}
