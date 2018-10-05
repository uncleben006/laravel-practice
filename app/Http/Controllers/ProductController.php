<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\ProductImage;
use App\Racket;
use App\Footwear;
use App\Bag;
use Log;
use Auth;
use Illuminate\Support\Facades\Storage;

use Image;
use Illuminate\Support\Str;

// Validator
use Validator;

class ProductController extends Controller
{
    /**
     * Display all products api
     *
     * @return \Illuminate\Http\Response
     */
    public function api()
    {
        // 把各類的產品資料庫都merge起來，去掉id，sortby product_id
        $racket = Racket::all();
        $footwear = Footwear::all();
        $bag = Bag::all();
        $product = [
            'racket'=>$racket,
            'footwear'=>$footwear,
            'bag'=>$bag,
        ];    
        return $product;
        
        $merge = Racket::all()->merge(Footwear::all());    
        return Racket::all();
    }
    /**
     * Display rackets api
     */
    public function racketApi()
    {
        return Racket::all();
    }
    /**
     * Display footwears api
     */
    public function footwearApi()
    {
        return Footwear::all();
    }
    /**
     * Display bags api
     */
    public function bagApi()
    {
        return Bag::all();
    }
    /**
     * Display single product api
     */
    public function singleApi($id)
    {
        return Product::find($id);
    }
    /**
     * Display images api
     */
    public function imagesApi($id)
    {
        return ProductImage::where('product_id', $id)->get();
    }    
    /**
     * Display product job. 
     * Including add, edit and delete function to adjust product database. 
     * Will only show on administrator's navigation.
     */
    public function job() 
    {
        return view('products.product-job');
    }
    /**
     * Display product form to create a new product.
     *
     */
    public function jobForm()
    {
        return view('products.product-job-form');
    }       
    /**
     * Display product detail page.
     *
     * @param  int  $id
     */
    public function showDetail($category, $id)
    {
        $array = [];
        switch ($category) {
            case 'racket';
                $array = Product::find($id)->racket()->first();
                break;
            case 'footwear';
                $array = Product::find($id)->footwear()->first();
                break;
            case 'bag';
                $array = Product::find($id)->bag()->first();
                break;
            case 'apparel';
                return "You choose Apparel";
                break;
            case 'accessory';
                return "You choose Accessories";
                break;
        }
        return view('products.product-detail', [
            'product' => $array,
        ]);
    }
    /**
     * Display products rackets list
     */
    public function showRackets()
    {
        return view('products.product-rackets');
    }
    /**
     * Display products footwears list
     */
    public function showFootwears()
    {
        return view('products.product-footwears');
    }
    /**
     * Display products bags list
     */
    public function showBags()
    {
        return view('products.product-bags');
    }    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name'=>'required:',
            'price'=>'required|integer',
        ]);       
        
        if ($validate->fails()) {
            return redirect('/products/job/new/')
                        ->withErrors($validate)
                        ->withInput();
        }

        $product = Product::create([
            'name'=>$request->name
        ]);        
        $product_id = $product->id;
        self::storeImage($product_id,$request);
        switch ($request->category) {
            case 'racket':
                self::storeRacket($product_id, $request);
                break;
            case 'footwear':
                self::storeFootwear($product_id, $request);
                break;
            case 'bag':
                self::storeBag($product_id, $request);
                break;
            case 'apparel':
                return "You choose Apparel";
                break;
            case 'accessory':
                return "You choose Accessories";
                break;
        }
        return redirect('/products/job');
    } 
    /**
     *  Store a racket into product database, and then store the images. 
     */
    public function storeRacket($id, $request)
    {
        $racket = Racket::create([
            'product_id'=>$id,
            'name'=>$request->name,
            'price'=>$request->price,
            'description'=>$request->description? $request->description : '',
            'series'=>$request->series? $request->series : '',
            'categories'=>$request->categories? $request->categories : '',
            'rank'=>$request->rank? $request->rank : '',
            'brands'=>$request->brands? $request->brands : ''
        ]);
    }    
    /**
     * Store a footwear into product database
     */
    public function storeFootwear($id, $request)
    {                 
        $racket = Footwear::create([
            'product_id'=>$id,
            'name'=>$request->name,
            'price'=>$request->price,
            'description'=>$request->description? $request->description : '',
            'series'=>$request->series? $request->series : '',
            'categories'=>$request->categories? $request->categories : '',
            'rank'=>$request->rank? $request->rank : '',
            'brands'=>$request->brands? $request->brands : ''
        ]);
    }  
    /**
     * Store a bag into product database
     */
    public function storeBag($id, $request)
    {
        $racket = Bag::create([
            'product_id'=>$id,
            'name'=>$request->name,
            'price'=>$request->price,
            'description'=>$request->description? $request->description : '',
            'series'=>$request->series? $request->series : '',
            'categories'=>$request->categories? $request->categories : '',
            'rank'=>$request->rank? $request->rank : '',
            'brands'=>$request->brands? $request->brands : ''
        ]);
    }   
    /**
     * Store images into product image database.
     */
    public function storeImage($id, $request)
    {                
        $image_path = '/public/images/'.$id.'/';
        if($request->images){
            foreach ($request->images as $image) {
                echo 
                $fileName = $image->getClientOriginalName();     
                $image->storeAs($image_path, $fileName);
                ProductImage::create([
                    'product_id' => $id,
                    'filename' => $fileName
                ]);
            }   
        }                
    }
    /**
     * Display the product edit form to edit the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($category, $id)
    {
        $array = [];
        switch ($category) {
            case 'racket';
                $array = Product::find($id)->racket()->first();
                break;
            case 'footwear';
                $array = Product::find($id)->footwear()->first();
                break;
            case 'bag';
                $array = Product::find($id)->bag()->first();
                break;
            case 'apparel';
                return "You choose Apparel";
                break;
            case 'accessory';
                return "You choose Accessories";
                break;
        }
        return view('products.product-edit', [
            'product' => $array,
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $category, $id)
    {        
        $validate = Validator::make($request->all(), [
            'name'=>'required:',
            'price'=>'required|integer',
        ]);       
        
        if ($validate->fails()) {
            return redirect("/products/$id/$category/edit/")
                        ->withErrors($validate)
                        ->withInput();
        }     
        // 如果有上傳圖片，則刪除原本資料結構裡的圖片，
        // 同時刪除資料庫裡的圖片，並新增新的圖

        $validate = Validator::make($request->all(), [
            'name'=>'required:',
            'price'=>'required|integer',
        ]);       
        
        if ($validate->fails()) {
            return redirect("/products/$id/edit/")
                        ->withErrors($validate)
                        ->withInput();
        }    

        // 改 Product 資料庫 (主資料庫)
        Product::where('id', $id)
        ->update([
            'name' => $request->name,
        ]);

        // 改圖
        self::updateImage($id,$request);

        // 如果 User 選的類別汗腺在是一樣的，那就在現在這個 Model更新，若否則刪除並再新的 Model 新增
        if($request->category==$category){
            switch ($category) {
                case 'racket';
                    self::updateRacket($id, $request);     
                    break;
                case 'footwear';
                    self::updateFootwear($id, $request);
                    break;
                case 'bag':
                    self::updateBag($id, $request);
                    break;
                case 'apparel':
                    return "You choose Apparel";
                    break;
                case 'accessory':
                    return "You choose Accessories";
                    break;
            }
        }
        // 若否則刪除並再新的 Model 新增
        else {
            // self::destroy($id);
            switch ($category) {
                case 'racket';
                    Racket::where('product_id', $id)->delete();
                    break;
                case 'footwear';
                    Footwear::where('product_id', $id)->delete();
                    break;
                case 'bag':
                    Bag::where('product_id', $id)->delete();
                    break;
                case 'apparel':
                    Product::destroy($id);
                    break;
                case 'accessory':
                    Product::destroy($id);
                    break;
            }
            switch ($request->category) {
                case 'racket':
                    self::storeRacket($id, $request);
                    break;
                case 'footwear':
                    self::storeFootwear($id, $request);
                    break;
                case 'bag':
                    self::storeBag($id, $request);
                    break;
                case 'apparel':
                    return "You choose Apparel";
                    break;
                case 'accessory':
                    return "You choose Accessories";
                    break;
            }
        }
        return redirect('/products/job');
    }
    /**
     *  Update racket
     */
    public function updateRacket($id, $request)
    {              
        $racket = Racket::where('product_id', $id)
        ->update([
            'product_id'=>$id,
            'name'=>$request->name,
            'price'=>$request->price,
            'description'=>$request->description? $request->description : '',
            'series'=>$request->series? $request->series : '',
            'categories'=>$request->categories? $request->categories : '',
            'rank'=>$request->rank? $request->rank : '',
            'brands'=>$request->brands? $request->brands : ''
        ]);
    }
    /**
     *  Update footwear
     */
    public function updateFootwear($id, $request)
    {    
        $racket = Footwear::where('product_id', $id)
        ->update([
            'product_id'=>$id,
            'name'=>$request->name,
            'price'=>$request->price,
            'description'=>$request->description? $request->description : '',
            'series'=>$request->series? $request->series : '',
            'categories'=>$request->categories? $request->categories : '',
            'rank'=>$request->rank? $request->rank : '',
            'brands'=>$request->brands? $request->brands : ''
        ]);
    }
    /**
     *  Update bag
     */
    public function updateBag($id, $request)
    {    
        $racket = Bag::where('product_id', $id)
        ->update([
            'product_id'=>$id,
            'name'=>$request->name,
            'price'=>$request->price,
            'description'=>$request->description? $request->description : '',
            'series'=>$request->series? $request->series : '',
            'categories'=>$request->categories? $request->categories : '',
            'rank'=>$request->rank? $request->rank : '',
            'brands'=>$request->brands? $request->brands : ''
        ]);
    }
    /**
     * Update images.
     */
    public function updateImage($id, $request)
    {        
        if($request->images){
            Storage::deleteDirectory("/public/images/$id");
            $originalImages = ProductImage::where('product_id',$id);
            $originalImages->delete();     
            $image_path = '/public/images/'.$id.'/';
            foreach ($request->images as $image) {
                $fileName = $image->getClientOriginalName();                     
                $image->storeAs($image_path, $fileName);
                ProductImage::create([
                    'product_id' => $id,
                    'filename' => $fileName
                ]);
            }
        }      
    }    
    /**
     * Remove the specified file from storage, including the images inside.
     * Remove the information from products DB and product_images DB.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Storage::deleteDirectory("/public/images/$id");
        Product::destroy($id);
        return redirect('/products/job');
    }
}




    /**
     * Display products list
     */
    // public function list()
    // {
    //     return view('products.product-list');
    // }    
    /**
     * Add product into shopping cart
     */
    // public function add_cart(Request $request, $id)
    // {
    //     $prev = $request->session()->get('cart');
    //     $arr = [];
    //     if( $prev != null ){
    //         $arr = $prev;
    //     }
    //     $arr[] = $id;
    //     $request->session()->put('cart', $arr);
    //     return (['status'=>true]);
    // }
    /**
     * Display shopping cart api
     */
    // public function list_cart(Request $request)
    // {
    //     // return json_decode($request->session()->get('cart'));
    //     // 取得當初存入 session 中的 id 陣列，然後再排序、歸類
    //     $session_value = $request->session()->get('cart');
    //     if ($session_value){
    //         sort($session_value);   
    //         $session_value = array_count_values($session_value);   
    //     } else {
    //         $session_value = array();
    //     }

    //     // 用 session id 陣列來搜尋 product，取得完整的產品資料，再傳給 $prod_list      
    //     $prod_list = [];         
    //     $i = 0;
    //     foreach($session_value as $key => $value){
    //         if(Product::find($key)){
    //             $prod_list[] = Product::find($key);
    //             $prod_list[$i]->{'quantity'} = $value;
    //         }
    //         $i++;
    //     };
    //     // echo $i;   
    //     // print_r($prod_list);
    //     return $prod_list;
    // }
    /**
     * Display shopping cart interface
     */
    // public function cart(Request $request)
    // {
    //     return view('ecommerce.product-cart');
    // }