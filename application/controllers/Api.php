<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'/libraries/REST_Controller.php');

class Api extends REST_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 
	function __construct()
	{
		parent::__construct();
		$this->load->model('user','',TRUE);
        $this->load->model('device','',TRUE);
        $this->load->model('category','',TRUE);
        $this->load->model('profile','',TRUE);
		$this->load->model('product','',TRUE);
    	
        $this->user_id = '';
        $this->token   = '';

        $headers = getallheaders();

	   if(!in_array($this->router->method, $this->config->item('allowed_calls_without_token')))
       {
            if(isset($headers['Token']))
            {
                if(isset($headers['Userid']))
                {
                    if(!$this->device->validToken($headers['Userid'],$headers['Token']))
                    {
                        $data["header"]["error"] = "1";
                        $data["header"]["message"] = "Please provide valid token";
                        $this->response($data, 200);                     
                    }
                    else
                    {
                        $this->user_id = $headers['Userid'];
                        $this->token = $headers['Token'];
                    }  
                }   
                else
                {
                    $data["header"]["error"] = "1";
                    $data["header"]["message"] = "Please provide user id (header)";
                    $this->response($data, 200);              
                } 
            } 
            else
            {
                $data["header"]["error"] = "1";
                $data["header"]["message"] = "Please provide access token";
                $this->response($data, 200);       
            }    
        
       }
	   else
	   {
		    if(isset($headers['Userid']))
			{
				$this->user_id = $headers['Userid'];
			}
			
			if(!$this->user_id)
			{
				$this->user_id = $this->post('user_id');
			}
	   }
        
       
	 }

	public function index()
	{
		$this->load->view('welcome_message');
	}

    function updateDevice_post()
    {
        $device_id = $this->post('device_id');
        $user_id = $this->post('user_id');     
        $type = $this->post('device_type');     
        $token = $this->token;

        if(!$device_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Please provide device id";
            $this->response($data, 200);
        }

        if(!$user_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Please provide user id";
            $this->response($data, 200);
        }

        $user_present = $this->user->checkUserById($user_id);
        
        

        if($user_present == false)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "No user present with this id.";
            $this->response($data, 200);
        }   
        else
        {
            
            $device = $this->device->get_user_device($user_id, $device_id);
            if(count($device) > 0)
            {
                //update device table
                $device_data = array('uid'=>$device_id, 'type'=>$type);
                $this->device->edit_device($user_id, $device_data);
            }
            else
            {
                if(isset($type) && isset($device_id))
                {

                    //insert device table
                    $device_data = array('user_id'=>$user_id,'uid'=>$device_id, 'type'=>$type);
                    $this->device->insert_device($device_data);
                }
            }

            $data["header"]["error"] = "0";
            $data["header"]["message"] = "Device id updated.";
            $this->response($data, 200);
        }
    }

    function logout_post()
    {
        //$user_id = $headers['Userid'];
        //$token = $headers['Token'];
        $this->device->delete_device($this->user_id,$this->token);
        $data["header"]["error"] = "0";
        $data["header"]["message"] = "User logout successfully";
        $this->response($data, 200);
    }

    function signup_post()
    {
        $name           = $this->post('name');
        $parent_user_id = $this->post('parent_user_id');
        $email          = $this->post('email');
        $password       = $this->post('password');
        $role_id        = $this->post('role_id');
        $device_id      = $this->post('device_id');
        $device_type    = $this->post('device_type');
        $created        = date('Y-m-d H:i:s');
        $updated        = date('Y-m-d H:i:s');
        $status         = 1;

        if(!$name)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Name is required";
            $this->response($data, 200);
        }
        if(!isset($parent_user_id))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Parent ID is required";
            $this->response($data, 200);
        }
        if(!$email)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Email is required";
            $this->response($data, 200);
        }   
        if(!$password)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Password is required";
            $this->response($data, 200);
        }
        if(!isset($role_id))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Role is required";
            $this->response($data, 200);
        }

        $already_present = $this->user->checkUser($email);
        if($already_present !== false)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "User already present with this email";
            $this->response($data, 200);   
        } 

        $user = array("name"=>$name,"parent_user_id"=>$parent_user_id,"email"=>$email,"password"=>md5($password),"status"=>$status,"role_id"=>$role_id,"updated"=>$updated,"created"=>$created);
        
        // $temp_image_url = $this->__uploadFile($this->config->item('user_image_base'), asset_url('img/users'));

        // if($temp_image_url !== "")
        // {
        //     $user['image'] = $temp_image_url;
        // }
        
        // if($facebook_id !== '' && $temp_image_url === ""){

        //     $user['image'] = getFacebookImage($facebook_id);
        	
        // }

        
        $user_id = $this->user->add_user($user);

        //insert device table
        if(isset($device_type) && isset($device_id))
        {
            $device_data = array('user_id'=>$user_id,'uid'=>$device_id, 'type'=>$device_type);
            $this->device->insert_device($device_data);
        }  

        $data["header"]["error"]   = "0";
        $data["header"]["message"] = "Signup successfull";
        $this->response($data, 200);
    }

    function imageTest_post()
    {
        $temp_image_url = $this->__uploadFile($this->config->item('product_image_base'), asset_url('img/products'));
        debug($temp_image_url,1);
    }

    function __uploadFile($uploadDir, $baseUrl)
    {
        $_imageURL = array();
        if(isset($_FILES))
        {
            if(isset($_FILES['file']))
            {
                if(isset($_FILES['file']['name']))
                {
                    $fileName = basename($_FILES['file']['name']);
                    
                    if($fileName)
                    {                           
                        $fileExtension = end((explode(".", $fileName)));
                        
                        $imageName = time().'.'.$fileExtension;
                        $uploadFile = $uploadDir . $imageName;

                        if(move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile))
                        {
                            $_imageURL['ext']  = $fileExtension;
                            $_imageURL['path'] = $baseUrl.'/'.$imageName;
                        }
                    }
                }   
            }
        }
        return $_imageURL;
    }

    function getSecurityQuestions_get()
    {
        $questions               = $this->profile->get_questions();
        $data["header"]["error"] = "0";
        $data['body']            = array("questions"=>$questions);
        $this->response($data, 200);
    }

    function setSecurityQuestion_post()
    {
        $security_question_id = $this->post('security_question_id');
        $security_answer      = $this->post('security_answer');
        $created              = date('Y-m-d H:i:s');
        $updated              = date('Y-m-d H:i:s');
        $status               = 1;

        if(!$security_question_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Security Question is required";
            $this->response($data, 200);
        }
        if(!$security_answer)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Security answer is required";
            $this->response($data, 200);
        }

        $profile_id = $this->profile->add_user_detail(array("user_id"=>$this->user_id,"security_question_id"=>$security_question_id,"security_answer"=>$security_answer,"created"=>$created,"updated"=>$updated,"status"=>$status));
        $data["header"]["error"] = "0";
        $data["header"]["message"] = "Success";
        $data['body'] = array();
        $this->response($data, 200);
    }

    function setBusinessInfo_post()
    {
        $name = $this->post('name');
        $logo = "";
        $temp_image_data = $this->__uploadFile($this->config->item('store_image_base'), asset_url('img/stores'));

        if(count($temp_image_data) > 0)
        {
            $logo    = $temp_image_data['path'];
        }

        if(!$name)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Name is required";
            $this->response($data, 200);
        }
        if(!$logo)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Logo is required";
            $this->response($data, 200);
        }

        $created = date('Y-m-d H:i:s');
        $updated = date('Y-m-d H:i:s');
        $status  = 1;

        $profile_id = $this->profile->add_user_store(array("user_id"=>$this->user_id,"name"=>$name,"logo"=>$logo,"created"=>$created,"updated"=>$updated,"status"=>$status));
        $data["header"]["error"] = "0";
        $data["header"]["message"] = "Success";
        $data['body'] = array();
        $this->response($data, 200);
    }

    function setBankAccountInfo_post()
    {
        $bank_name      = $this->post('bank_name');
        $account_number = $this->post('account_number');
        $created        = date('Y-m-d H:i:s');
        $updated        = date('Y-m-d H:i:s');
        $status         = 1;

        if(!$bank_name)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Bank Name is required";
            $this->response($data, 200);
        }
        if(!$account_number)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Account Number is required";
            $this->response($data, 200);
        }

        $this->profile->add_user_bank(array("user_id"=>$this->user_id,"bank_name"=>$bank_name,"account_number"=>$account_number,"created"=>$created,"updated"=>$updated,"status"=>$status));
        $data["header"]["error"] = "0";
        $data["header"]["message"] = "Success";
        $data['body'] = array();
        $this->response($data, 200);

    }

    function editProfile_post()
    {
        $email = $this->post('email');
        $device_id = $this->post('device_id');
        $device_type = $this->post('device_type');
        
        $already_present = $this->user->checkUserById($this->user_id);
        if($already_present === false)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "No user present with this username";
            $this->response($data, 200);   
        } 

        $user = array("first_name"=>$first_name,"last_name"=>$last_name,"facebook_id"=>$facebook_id);
        $temp_image_url = $this->__uploadFile($this->config->item('user_image_base'), asset_url('img/users'));

        if($temp_image_url !== "")
        {
            $user['image'] = $temp_image_url;
        }

        $user_id = $this->user->edit_user($this->user_id, $user);

        //edit device table
        if(isset($device_type) && isset($device_id))
        {
            $device_data = array('uid'=>$device_id, 'type'=>$device_type);
            $this->device->edit_device($this->user_id, $device_data);
        }  

        $data["header"]["error"] = "0";
        $data["header"]["message"] = "Profile edit successfull";
        $this->response($data, 200);
    }

	function login_post()
    {
    	$data = array();

        $email    = $this->post('email');
        $password    = $this->post('password');
        $device_id   = $this->post('device_id');
        $device_type = $this->post('device_type');
        $os_version  = $this->post('os_version');

        if(!$email || !$password)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Email or password is incorrect";
            $this->response($data, 200);
        }
        else
        {
            $result = $this->user->login($email, $password);
            
            if($result !== false)
            {
                $user = (array) $result[0];
                $token = bin2hex(openssl_random_pseudo_bytes(16));    
                
                if(md5($password) === $user['new_password'])
                {
                    $this->user->edit_user($user['user_id'], array('password'=>md5($password)));
                }    

                //insert device table
                if(isset($device_type) && isset($device_id))
                {
                    $device_data = array('user_id'=>$user['user_id'],'uid'=>$device_id, 'type'=>$device_type,'token'=>$token);
                    //$this->device->insert_device($device_data);

                    $device = $this->device->get_user_device($user['user_id'], $device_id);
                    if(count($device) > 0)
                    {
                        //update device table
                        $device_data = array('uid'=>$device_id, 'type'=>$device_type,'token'=>$token,"os_version"=>$os_version);
                        $this->device->edit_device($user['user_id'], $device_data);
                    }
                    else
                    {
                        //insert device table
                        $device_data = array('user_id'=>$user['user_id'],'uid'=>$device_id, 'type'=>$device_type,'token'=>$token,"os_version"=>$os_version);
                        $this->device->insert_device($device_data);
                    }    
                }    
                
                $array['user_id']          = $user['user_id'];
                $array['Token']            = $token;
                $array['user']             = $user;
                $data["header"]["error"]   = "0";
                $data["header"]["message"] = "Login successfully";
                $data['body']              = $array;
            }
            else
            {
                $data["header"]["error"]   = "1";
                $data["header"]["message"] = "Username or password is incorrect";
            }

            $this->response($data);
        }
    }

    function changePassword_post()
    {
        $old_password = $this->post('old_password');
        $new_password = $this->post('new_password');

        $user = $this->user->checkUserById($this->user_id);

        if(md5($old_password) !== $user[0]->password)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Please provide correct old password";
            $this->response($data, 200);
        }    
        else
        {
            $this->user->edit_user($this->user_id, array("password"=>md5($new_password)));

            $data["header"]["error"]   = "0";
            $data["header"]["message"] = "Password change successfully";
            $this->response($data, 200);
        }    
    }

    function forgetPassword_post()
    {
    	$data = array();

        $username = $this->post('email');

        if(!$username)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Please provide email";
            $this->response($data,200);
        }
        else
        {
         
            $user = $this->user->checkUser($username);
            if(!$user)
            {
                $data["header"]["error"]   = "1";
                $data["header"]["message"] = "No user found with this email";
                $this->response($data,200);
            }   
            else
            {
                $user          = (array) $user[0];
                $temp_password = rand_string(8);
                $md5           = md5($temp_password);

                $user['new_password'] = $md5;
                $this->user->edit_user($user['user_id'],$user);
                
                    //email work here
                $subject = 'Your password has been changed successfully';
                $message = 'Your temporary password is '.$temp_password;
                $email   = array('to'=>$user['email'], 'from'=>$this->config->item('default_email'),'subject'=>$subject, 'message'=>$message);
                
                sendEmail($email);
                $data["header"]["error"]   = "0";
                $data["header"]["message"] = 'Please check your email';
                $this->response($data,200);
            } 
               
            
        }
    }

    function saveCategory_post()
    {
        $store_id  = $this->post('store_id');
        $parent_id = $this->post('parent_id');
        $name      = $this->post('name');
        $created   = date('Y-m-d H:i:s');
        $updated   = date('Y-m-d H:i:s');
        $status    = 1;

        if(!$store_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Store id is required";
            $this->response($data, 200);
        }
        if(!isset($parent_id))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Parent category is required";
            $this->response($data, 200);
        }
        if(!$name)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Category name is required";
            $this->response($data, 200);
        }

        $category_id = $this->category->add_category(array("user_id"=>$this->user_id,"store_id"=>$store_id,"parent_id"=>$parent_id,"name"=>$name,"created"=>$created,"updated"=>$updated,"status"=>$status));
        
        $data["header"]["error"]   = "0";
        $data["header"]["message"] = "Success";
        $data['body']              = array();
        $this->response($data, 200);
    }

    function editCategory_post()
    {
        $category_id = $this->post('category_id');
        $parent_id   = $this->post('parent_id');
        $name        = $this->post('name');
        $updated     = date('Y-m-d H:i:s');
        $status      = 1;

        if(!$category_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Category id is required";
            $this->response($data, 200);
        }
        if(!isset($parent_id))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Parent category is required";
            $this->response($data, 200);
        }
        if(!$name)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Category name is required";
            $this->response($data, 200);
        }

        $result = $this->category->edit_category($category_id, array("parent_id"=>$parent_id,"name"=>$name,"updated"=>$updated,"status"=>$status));
        if($result)
        {
            $data["header"]["error"]   = "0";
            $data["header"]["message"] = "Success";
            $data['body']              = array();
            $this->response($data, 200);    
        }    
        else
        {
            $data["header"]["error"]   = "1";
            $data["header"]["message"] = "Some error";
            $this->response($data,200);
        }    
        
    }

    function getCategory_post()
    {
        $category_id = $this->post('category_id');
        if(!$category_id)
        {
            $data["header"]["error"]   = "1";
            $data["header"]["message"] = "Provide category";
            $this->response($data, 200);
        }

        $category_detail         = $this->category->get_category_detail($category_id);
        $data["header"]["error"] = "0";
        $data['body']            = array("category_detail"=>$category_detail);
        $this->response($data, 200);
    }

    function saveProduct_post()
    {
        $name        = $this->post('name');
        $description = $this->post('description');
        $price       = $this->post('price');
        $store_id    = $this->post('store_id');
        $category_id = $this->post('category_id');
        $created     = date('Y-m-d H:i:s');
        $updated     = date('Y-m-d H:i:s');
        $status      = 1;

        if(!$name)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide product name";
            $this->response($data, 200);
        }
        if(!$description)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide product description";
            $this->response($data, 200);
        }
        if(!isset($product_id))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide product price";
            $this->response($data, 200);
        }
        if(!isset($store_id))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide store";
            $this->response($data, 200);
        }
        if(!isset($category_id))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide product category";
            $this->response($data, 200);
        }

        $product_id = $this->product->add_product(array("user_id"=>$this->user_id,"store_id"=>$store_id,"name"=>$name,"description"=>$description,"price"=>$price,"created"=>$created,"updated"=>$updated,"status"=>$status));
        //category work here
        $this->category->add_product_category(array("product_id"=>$product_id,"category_id"=>$category_id));
        
        $temp_image_data = $this->__uploadFile($this->config->item('user_image_base'), asset_url('img/products'));

        if(count($temp_image_data) > 0)
        {
            $product_media = array("product_id"=>$product_id,"file_name"=>$temp_image_data['path'],"media_type"=>$temp_image_data['ext'],"created"=>$created,"updated"=>$updated,"status"=>$status);

            $this->product->add_product_media($product_media);
        }


        $data["header"]["error"]   = "0";
        $data["header"]["message"] = "Success";
        $data['body']              = array();
        $this->response($data, 200);
    }

    function editProduct_post()
    {}

    function getCategories_post()
    {
        $categories              = $this->category->get_all_categories($this->user_id);
        $data["header"]["error"] = "0";
        $data['body']            = array("categories"=>$categories);
        $this->response($data, 200);
    }

    function getProducts_post()
    {
        $products                = $this->product->get_all_products($this->user_id);
        $data["header"]["error"] = "0";
        $data['body']            = array("products"=>$products);
        $this->response($data, 200);
    }

    function getProductsByCategory_post()
    {
        $category_id = $this->post('category_id');
        if(!$category_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide category";
            $this->response($data, 200);
        }

        $products    = $this->product->get_products_by_category($category_id);
        
        $data["header"]["error"] = "0";
        $data['body']            = array("products"=>$products);
        $this->response($data, 200);
    }

    function getProduct_post()
    {
        $product_id = $this->post('product_id');
        if(!$product_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide product";
            $this->response($data, 200);
        }

        $category_detail         = $this->product->get_product_detail($product_id);
        $data["header"]["error"] = "0";
        $data['body']            = array("product_detail"=>$product_detail);
        $this->response($data, 200);   
    }

    
}