<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

//Clases del Modelo de datos
use App\Customer;
use App\Product;
use App\CustomerProduct;
use App\Order;
use App\OrderDetail;

class OrderController extends Controller
{
    const ERROR_CUSTOMER_DATA = "Wrong customer data";
    const ERROR_NO_PRODUCTS = "No products sent";
    const ERROR_PRODUCTS_FORMAT = "Wrong products data";
    const ERROR_PRODUCTS_QUANTITY = "Invalid quantity of products";
    const ERROR_PRODUCTS_INVALID = "Invalid products";
    const ERROR_DATE_DATA = "Wrong date data";

    private $MAX_PRODUCTS_QUANTITY = 5;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Se validan los datos del cliente
        if(
            !isset($request->customer_id)  
            || !intval($request->customer_id)
            || intval($request->customer_id) < 0
        ){
            return Response::make(self::ERROR_CUSTOMER_DATA, 400);
        }

        $customerId = $request->customer_id;

        //Se realiza validación de existencia de rango de fechas
        if(!isset($request->date_ini) || !isset($request->date_end)){
            return Response::make(self::ERROR_DATE_DATA, 400);
        }else{
            $dateIni = $request->date_ini;
            $dateFin = $request->date_end;

            //Se realiza validación del formato de fechas dadas
            $auxIni = explode("-",$dateIni);
            $auxFin = explode("-",$dateFin);
            if(
                count($auxIni) == 3 || count($auxFin) == 3
                || checkdate(intval($auxIni[1]),intval($auxIni[2]),intval($auxIni[0]))
                || checkdate(intval($auxFin[1]),intval($auxFin[2]),intval($auxFin[0]))
            ){
                //Se obtienen las ordenes con los filtros dados
                $orders = Order::whereRaw('customer_id = ? and creation_date between ? and ?',array($customerId, $dateIni, $dateFin))->get();

                //Estructura de datos para retornar
                $data = array();

                foreach($orders as $order){

                    //Se construye la data para los productos de la orden
                    $prods = array();
                    foreach($order->orderDetails as $prod){
                        array_push($prods,[
                            "product_id" => $prod->product->product_id,
                            "product_name" => $prod->product->name,
                            "product_decription" => $prod->product_description,
                            "quantity" => $prod->quantity,
                            "price" => $prod->price,
                            "price_unit" => $prod->product->price
                        ]);
                    }

                    //Se construye la data de las ordenes
                    array_push($data,[
                        "order_id" => $order->order_id,
                        "creation_date" => $order->creation_date,
                        "delivery_address" => $order->delivery_address,
                        "total" => $order->total,
                        "products" => $prods
                    ]);
                }

                return response()->json($data);
            }else{
                return Response::make(self::ERROR_DATE_DATA, 400);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //Se validan los datos de la orden
        if(
            !isset($request->customer_id) || !isset($request->delivery_address) 
            || !intval($request->customer_id) || intval($request->customer_id) < 0
            || !is_string($request->delivery_address)
        ){
            return Response::make(self::ERROR_CUSTOMER_DATA, 400);
        }

        $customerId = $request->customer_id;
        $deliveryAddress = $request->delivery_address;
        
        $products = json_decode($request->products,true);

        if(Customer::find($customerId)){
            $resVal = $this->validateInputProducts($products);
            if($resVal === true){
                $resVal = $this->validateDBProducts($products,$customerId);
                if($resVal === true){
                    $orderDetailData = array(); //Auxiliar para almacenar order_detail
                    $orderTotal = 0; //Precio total de la orden

                    foreach($products as $product){
                        //Se obtiene el producto de la DB para ser añadido a la orden
                        $productDB = Product::find($product['id']);

                        $price = $product['quantity'] * $productDB->price;

                        //Se cosntruye la estructura de datos para almacenar order_detail
                        array_push($orderDetailData,array(
                            "product_id" => $productDB->product_id,
                            "product_description" => $productDB->product_description,
                            "quantity" => $product['quantity'],
                            "price" => $price
                        ));

                        $orderTotal += $price;
                    }

                    $order = new Order();
                    $order->customer_id = $customerId;
                    $order->creation_date =  date('Y-m-d');
                    $order->delivery_address = $deliveryAddress;
                    $order->total = $orderTotal;
                    $order->save();

                    foreach($orderDetailData as $row){
                        $orderDetail = new OrderDetail();
                        $orderDetail->order_id = $order->order_id;
                        $orderDetail->product_id = $row["product_id"];
                        $orderDetail->product_description = $row["product_description"];
                        $orderDetail->price = $row["price"];
                        $orderDetail->quantity = $row["quantity"];
                        $orderDetail->save();
                    }
                    return response()->json([
                        "order_id" => $order->order_id,
                        "customer_id" => $order->customer_id,
                        "creation_date" => $order->creation_date,
                        "delivery_address" => $order->delivery_address,
                        "total" => $order->total,
                        "order_detail" => $orderDetailData,
                    ]);
                }else{
                    return Response::make($resVal, 400);
                }
            }else{
                return Response::make($resVal, 400);
            }
        }else{
            return Response::make(self::ERROR_CUSTOMER_DATA, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return Order::where('order_id', $id)->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //No implementado
        return Response::make('', 405);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //No implementado
        return Response::make('', 405);
    }


    /**
     * Valida que los productos recibidos estén en una estructura de datos válida.
     * Valida que la cantidad de productos no supere el limite dado;
     * @param $products Estructura de datos a evaluar
     * @return true en caso de estructura válida o un código de error
     */
    private function validateInputProducts($products){
        if(is_array($products)){
            if(count($products)>0){
                //Si hay productos

                $countProds = 0;

                foreach($products as $product){
                    //Se valida la erstructura de cada producto
                    if(
                        (!isset($product["id"]) || !isset($product["quantity"])) 
                        || (!is_int($product["id"]) || !is_int($product["quantity"]))
                        || ($product["id"] < 0 || $product["quantity"] < 0)
                    ){
                        return self::ERROR_PRODUCTS_FORMAT;
                    }else{
                        $countProds += $product["quantity"];

                        //Se valida la canitdad de productos
                        if($countProds > $this->MAX_PRODUCTS_QUANTITY){
                            return self::ERROR_PRODUCTS_QUANTITY;
                        }
                    }
                }
            }else{
                //Si no hay productos
                return self::ERROR_PRODUCTS_QUANTITY;
            }
        }else{
            //Si la variable de productos no es un arreglo válido
            return self::ERROR_PRODUCTS_FORMAT;
        }

        //Si no falla ninguna validación retorna true
        return true;
    }

    /**
     * Evalúa que los ids de productos existan en la DB y que estén relacionados con el cliente dado.
     * @param $products
     * @param $customerId
     * @return true si los productos son válidos, de lo contrario retorna código de error por productos inválidos.
     */
    private function validateDBProducts($products,$customerId){
        foreach($products as $product){
            if(CustomerProduct::whereRaw('customer_id = ? and product_id = ?',array($customerId, $product["id"]))->count() <= 0){
                return self::ERROR_PRODUCTS_INVALID;
            }
        }
        return true;
    }
}
