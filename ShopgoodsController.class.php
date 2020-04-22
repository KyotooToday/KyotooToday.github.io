
public function shopgoods_order(){
		if(isset($_SESSION["m_id"]) && (int)($_SESSION["m_id"])>0){		
			if(!$_POST["cart_id"]){
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><script>location.href="'.$_SERVER["HTTP_REFERER"].'"; </script>';	// 返回到前一页
			}else{
				$in = implode(',',$_POST["cart_id"]);

				$Model = new \Think\Model(); // 实例化一个model对象,没有对应任何数据表
				$query = "select * from `jx_shopcart` left join `jx_shopgoods` ON `jx_shopcart`.shopgoods_id=`jx_shopgoods`.id left join `jx_specprice` ON `jx_shopcart`.shopgoods_id=`jx_specprice`.shopgoods_id and `jx_shopcart`.specs_id=`jx_specprice`.specs_id left join `jx_specs` ON `jx_specprice`.specs_id=`jx_specs`.id where `jx_shopcart`.member_id=" . $_SESSION["m_id"] . " and `jx_shopcart`.cart_id in (" . $in . ");";
                $cartList = $Model->query($query);		
				$this->assign("cartList",$cartList);

				$addrList = M("Address")->join('`tp_province` on `jx_address`.ProvinceID=`tp_province`.ProvinceID','LEFT')->join('`tp_city` on `jx_address`.city_id=`tp_city`.id','LEFT')->where("member_id=" . $_SESSION["m_id"])->field('`jx_address`.*,`tp_province`.ProvinceName,`tp_city`.CityName')->select();						
				$this->assign("addrList",$addrList);
				
				if($_GET['addrid']){
\Think\Log::write(' _GET[addrid]: ');   // cjq add
\Think\Log::write(var_export($_GET['addrid'],true));   // cjq add
                    $thisaddr = $_GET['addrid'];
				}else{
\Think\Log::write(' ok1: ');   // cjq add
					$thisaddr = $addrList[0]['id'];
\Think\Log::write(" 1thisaddr: " . $thisaddr);   // cjq add
					foreach($addrList as $k1=>$v1){					
						if($v1['default1'] == 1){
							$thisaddr = $v1['id'];
							break;
						}
					}
\Think\Log::write(" 2thisaddr: " . $thisaddr);   // cjq add
				}
				$this->assign("thisaddr",$thisaddr);

				$pnum = 0;
				$sum = 0;
				foreach($_POST["cart_id"] as $k=>$v){					
					$pnum = $pnum+$_POST["num"][$k];									
					$sum = $sum+$_POST["subtotal"][$k];
				}
				$this->assign("pnum",$pnum);
				$this->assign("sum",$sum);

				$specModel = D('Admin/spec');
				$specData = $specModel->getLst();	
	
				if($specData){
					$this->assign("specData", $specData);
				}

				$this->display();
			}
		}else{
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><script>location.href="/index.php/home/member/login1.html"; </script>';
		}
	}
