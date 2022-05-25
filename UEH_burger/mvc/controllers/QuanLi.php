<?php
require_once './mvc/helper/authorization.php';
class QuanLi extends Controller{
    
    function Default(){
        if (!isAdmin()) {
            header("Location: ".BASE_URL.'login');
            exit();
        } 

        $this->view('quan-li', []);
    }
     // san pham
    function QuanLiSanPham() {
        if (!isAdmin()) {
            header("Location: ".BASE_URL.'login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $url = $_POST['url'];
            $qlModel = $this->model('QuanLiModel');
            $message = $qlModel->XoaSP($url);
            echo "<script>
            alert('".$message."');
            window.location.href='".BASE_URL."quan-li/quan-li-san-pham/';
            </script>"; 
        }
        else {
            $spModel = $this->model('SanPhamModel');
            $allSP = $spModel->TatCaSanPhamDM();
            $this->view('ql-sanpham', [
                'allSP' => $allSP
            ]);
        }
    }

    function ThemSanPham() {
        if (!isAdmin()) {
            header("Location: ".BASE_URL.'login');
            exit();
        }

        //if post to this
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $i = 0;
            $count = count($_POST);
            //categories
            $categories = [];
            foreach ($_POST as $key=>$post) {
                if ($i>4 && $i<$count-1) {
                    $categories[] = $post;
                }
                $i ++;
            }
            
            //khac
            $name = $_POST['name'];
            $price = $_POST['price'];
            $view_count = $_POST['view_count'];
            $images = $_POST['images'];
            $description = $_POST['description'];
            $tags = $_POST['tags'];
    

            $model = $this->model('QuanLiModel');
            $messsage = $model->ThemSP($name, $price, $images, $description, $tags, $view_count, $categories);
            echo "<script>
            alert('".$messsage."');
            window.location.href='".BASE_URL."quan-li/quan-li-san-pham/';
            </script>";          
        } 
        //not post, render themsp view
        else {
            $spModel = $this->model('SanPhamModel');
            $categories = $spModel->GetCategory(false);
            $this->view('them-sanpham', [
                'categories' => $categories
            ]);
        }
    }

    function ChiTietSanPham($url) {

        if (!isAdmin()) {
            header("Location: ".BASE_URL.'login');
            exit();
        }

        $spModel = $this->model('SanPhamModel');
        $SP = $spModel->GetSanPham($url);
        $this->view('ql-sanpham-chitiet', [
            'sanpham' => $SP,
        ]);
    }

    function ChinhSuaSanPham($url=null) {
        if (!isAdmin()) {
            header("Location: ".BASE_URL.'login');
            exit();
        }

        if ($url) {
            $spModel = $this->model('SanPhamModel');
            $SP = $spModel->GetSanPham($url);

            //get categories (khac categories cua san pham hien tai)
            $categories = $spModel->GetCategory(false);
            foreach ($categories as $val1) {
                if (!in_array($val1,$SP[1]))
                {
                    $categoriesView[]= $val1;
                }
                
            }
    
            $this->view('ql-sanpham-chinhsua', [
                'sanpham' => $SP,
                'diff-categories' => $categoriesView
            ]);
        }
        else {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $i = 0;
                $count = count($_POST);
                //categories
                $categories = [];
                foreach ($_POST as $key=>$post) {
                    if ($i>4 && $i<$count-2) {
                        $categories[] = $post;
                    }
                    $i ++;
                }
                
                $name = $_POST['name'];
                $price = $_POST['price'];
                $view_count = $_POST['view_count'];
                $images = $_POST['images'];
                $description = $_POST['description'];
                $tags = $_POST['tags'];
                $current_url = $_POST['url'];


                $model = $this->model('QuanLiModel');
                $message = $model->SuaSP($current_url, $name, $price, $images, $description, $tags, $view_count, $categories);
                echo "<script>
                alert('".$message."');
                window.location.href='".BASE_URL."quan-li/quan-li-san-pham/';
                </script>"; 
                
            } else {
                header('Location: '.BASE_URL.'quan-li/quan-li-san-pham');
            }
        }
    }

    // danh muc
    function QuanLiDanhMuc() {
        if (!isAdmin()) {
            header("Location: ".BASE_URL.'login');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['action'])) {
                if ($_POST['action'] == 'remove') {
                    if (isset($_POST['url'])) {
                        $url = $_POST['url'];
                        $qlModel = $this->model("QuanLiModel");
                        $message = $qlModel->XoaCategory($url);
                        echo "<script>
                        alert('".$message."');
                        window.location.href='".BASE_URL."quan-li/quan-li-danh-muc/';
                        </script>"; 
                    } 
                    else echo "Error!";
                }
                elseif ($_POST['action'] == 'edit') {
                    if (isset($_POST['url'])) {
                        $url = $_POST['url'];
                        $name = $_POST['name'];
                        $image = $_POST['image'];
                        $qlModel = $this->model("QuanLiModel");
                        $message = $qlModel->SuaCategory($url, $name, $image);
                        echo "<script>
                        alert('".$message."');
                        window.location.href='".BASE_URL."quan-li/quan-li-danh-muc/';
                        </script>"; 
                    }
                    else echo "Error!";
                }
                elseif ($_POST['action'] == 'add') {
                    //verify $_Post data!
                    $name = $_POST['name'];
                    $image = $_POST['image'];
                    $qlModel = $this->model("QuanLiModel");
                    $message = $qlModel->ThemCategory($name, $image);
                    echo "<script>
                        alert('".$message."');
                        window.location.href='".BASE_URL."quan-li/quan-li-danh-muc/';
                        </script>"; 

                }
                else {
                    echo 'Error!';
                }
            } else {
                echo 'Error!';
            }
        }
        else {
            $spModel = $this->model('SanPhamModel');
            $allDM = $spModel->GetCategory();
            $this->view('ql-danhmuc', [
                'allDM' => $allDM
            ]);
        }
    }

    function QuanLiDonHang() {
        if (!isAdmin()) {
            header("Location: ".BASE_URL.'login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['action'])) {
                if ($_POST['action'] == 'remove') {
                    if (isset($_POST['id'])) {
                        $id = $_POST['id'];
                        $qlModel = $this->model("QuanLiModel");
                        $message = $qlModel->XoaOrder($id);
                        echo "<script>
                        alert('".$message."');
                        window.location.href='".BASE_URL."quan-li/quan-li-don-hang/';
                        </script>"; 
                    } 
                    else echo "Error!";
                }
                else {
                    echo 'Error!';
                }
            } else {
                echo 'Error!';
            }
        }
        else {
        $qlModel = $this->model('QuanLiModel');
            $orders = $qlModel->QuanLiOrder();
            $this->view('ql-donhang', [
                'orders' => $orders
            ]);
        }
    }

    function QuanLiAnh() {
        if (!isAdmin()) {
            header("Location: ".BASE_URL.'login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $target_dir = "./public/img/sanpham/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $extension = explode('.',basename($_FILES["fileToUpload"]["name"]))[1];

            $qlModel = $this->model('QuanLiModel');
            $fileNameId = $qlModel->GetNewIdImage();
        
            if (isset($_POST["submit"])) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if ($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                echo "<script>
                        alert('File đã tồn tại');
                        window.location.href='" . BASE_URL . "quan-li/quan-li-anh/';
                        </script>";
                $uploadOk = 0;
            }


            // Allow certain file formats
            if (
                $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif"
            ) {
                echo "<script>
                        alert('Xin lỗi, chỉ file định dạng: JPG, JPEG, PNG là hợp lệ');
                        window.location.href='" . BASE_URL . "quan-li/quan-li-anh/';
                        </script>";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0
            ) {
                echo "<script>
                        alert('Upfile không thành công');
                        window.location.href='" . BASE_URL . "quan-li/quan-li-anh/';
                        </script>";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir.$fileNameId.'.'.$extension)) {
                    $imglink = STATIC_URL . 'img/sanpham/' . $fileNameId . '.' . $extension;
                    $message = $qlModel->ThemImage($imglink);
                    echo "<script>
                        alert('" . $message . "');
                        window.location.href='" . BASE_URL . "quan-li/quan-li-anh/';
                        </script>"; 
                } else {
                    echo "<script>
                        alert('Upfile không thành công');
                        window.location.href='" . BASE_URL . "quan-li/quan-li-anh/';
                        </script>";
                }
                

            }
        } else {
            $qlModel = $this->model("QuanLiModel");
            $images = $qlModel->GetImages();
            $this->view('ql-anh', [
                'images' => $images
            ]);
        }

    }
}

?>
