<?php
//
require_once('/var/www/stat.vm.ua/cis/application/lib/axapta.lib.php');
require_once('/var/www/stat.vm.ua/cis/application/lib/axpost.class.php');
/*******************************************************************************
 *
 *
 *
 ***/
class Axapta {
    public $session;
    public $xml_str;
    public $pages_names = array();
    public $values_names = array();
    public $attr_names = array();
    public $brand_names = array();
    public $item_names = array();
    public $active = true;
    public $errors = array();
    public $log_fle = 'sync';
    public $asort_values = array('АССОРТИМЕНТ'=>0,'Под заказ'=>1);  //////////////// АССОРТИМЕНТ
    public $langs_list = array( 'en-us'=>3, 'ua'=>2, 'ru'=>1 ); //////////////////// Языки из Ах
    
    function __construct($login='web', $pass='web', $test=false){
    
        //$file_ax_sesion = ROOT.'/lib/axapta.session';
        $file_ax_sesion = '/var/www/vm.ua/lib/axapta.session';
    
        if (!$this->session) {
		     $ses = file_get_contents($file_ax_sesion);
		     if (!$this->ChecSession($ses)) {
			     $this->Login('web', 'web');
			     file_put_contents($file_ax_sesion, $this->session);
			  }
		  }
    }
    
    
    function Login($login='web', $pass='web', $test=false){
        if ($login) {
            $xml = $this->post( $this->createXml('LoginShop', array(
                'User'=>$login,
					 'Password'=>$pass,
					 'IPAddress'=>'192.168.200.1' )
            ));
            $this->session = (string) $xml->Session;
        }
    }
    
    function ChecSession($session) {
        if ($session) {
            $this->session = $session;
            $xml = $this->post( $this->createXml('SessionTimeout', null) );

            if ( (string)$xml->record->Timeout  ) {
               return true;
            } else {
               $this->session = '';
               return false; }
        }
    }
    
     public function run_procedure($proc) {
         $xml_text='<?xml version="1.0" encoding="utf-8"?'.'>
            <daxrequest>
            <action>run</action>';
          if (isSet($proc['class'])) {
            $xml_text.='<class>'.$proc['class'].'</class>';
          } 
          $xml_text.='<method>'.$proc['name'].'</method>';
          $xml_text.='<params><Session>'.$this->session.'</Session>';
          if (isSet($proc['params'])) {
            foreach ($proc['params'] as $name=>$val) { 
             $xml_text.='            
            <'.$name.'>'.$val.'</'.$name.'>';
            }
           }
          $xml_text.='</params></daxrequest>';
         $xml = $this->post($xml_text);

        $values = array();
        foreach ($xml->record as $i=>$r) {
           $values[$i]=$r;// $values[ (string)$r->Item ][ (string)$r->Currency ] = (string)$r->Amount;
        }
        return $values;
    }
    
    public function getPages($lang_code='ru'){
        $xml = $this->post( $this->createXml('InventProductGroupName', null) );

        $names = array();
        foreach ($xml->record as $i=>$r) {
            $groupId = (string) $r->Attr;
            $name    = (string) $r->Name;
            $lang    = (string) $r->Language; //RU
            if ($lang==$lang_code) {
                $names[$groupId] = $name; }
            $this->pages_names[$groupId][$lang] = $name;
        }

        $xml = $this->post( $this->createXml('InventProductGroup', null) );

        $pages = array();
        foreach ($xml->record as $i=>$r) {
            $groupId = (string) $r->Group;
            $parent  = (string) $r->ParentGroup;
            if (empty($parent)) {
                $parent = 0; }
            $pages[$parent][$groupId] = $names[$groupId];
        }
        
        return $pages;
    }
    
    public function getOrderStatus($order) {
        
        $xml = $this->post('<?xml version="1.0" encoding="utf-8"?'.'>
        <daxrequest>
          <action>run</action>
          <class>Web_Order</class>
          <method>getOrderStatus</method>
          <params>
            <Session>'.$this->session.'</Session>
            <SalesOrder>'.$order.'</SalesOrder>
            <SalesPriceTemplate>catalog.php</SalesPriceTemplate>
          </params>
        </daxrequest>');
        if ($xml->record) {        
        foreach ($xml->record as $i=>$r) {
                $status[0] = (string) $r->Status;
                $status[1] = (string) $r->Editable;
                                                
            }
        } 
        if ($xml->error) {
            
            $status[0] = "Не найден";
            $status[1] = "false";
            }
        return $status;
    }
    
    public function getSetup() {
        $xml = $this->post( $this->createXml('InventAttributeSetup', null) );
        
        $setup = array();
            foreach ($xml->record as $i=>$r) {
                $aid = (string) $r->Attr;
                $g   = (string) $r->Groups;
                $a   = (string) $r->Values;
                
                $groups = explode(';',$g);
                $values = explode(';', $a);
                foreach($groups as $group) {
                    $setup[$group][] = array('aid'=>$aid, 'values'=>$values);
                }
            }
        return $setup;
    }

    public function InventAttrSetupGroup() {
        $xml = $this->post( $this->createXml('InventAttrSetupGroup', null) );
        
        $setup = array();
        foreach ($xml->record as $i=>$r) {
            $aid       = (string) $r->Attr;
            $group     = (string) $r->Group;
            $filter    = (string) $r->Filter;
            $attribute = (string) $r->Attribute;
            
            $setup[$group][$aid] = array('aid'=>$aid, 'filter'=>$filter,'attribute'=>$attribute);
        }

        return $setup;
    }

    public function InventAttrSetup() {
        $setup = $this->InventAttrSetupGroup();
        $xml   = $this->post( $this->createXml('InventAttrSetup', null) );

            foreach ($xml->record as $i=>$r) {
                $aid = (string) $r->Attr;
                $a   = (string) $r->Values;

                $values = explode(';', $a);
                foreach($setup as $g => $d) {
                    foreach ($d as $d_aid => $data) {
                        if ($d_aid==$aid) {
                            $setup[$g][$d_aid]['values'] = $values; }
                    }
                }
            }
        return $setup;
    }
    
    public function getAttributes($lang_code='ru') {
        $xml = $this->post( $this->createXml('InventAttributeName', null) );

        $attr = array();
        foreach ($xml->record as $i=>$r) {
            $aid    = (string) $r->Attr;
            $lang   = (string) $r->Language;
            $name   = (string) $r->Name;
            
            if ($lang==$lang_code) {
                $attr[$aid] = $name; }
            $this->attr_names[$aid][$lang] = $name;
        }
        return $attr;
    }

     public function run_ax_procedure($proc, $params) {
         $xml_text='<?xml version="1.0" encoding="utf-8"?'.'>
            <daxrequest>
            <action>run</action>';
          if (isSet($proc['class'])) {
            $xml_text.='<class>'.$proc['class'].'</class>';
          } 
          $xml_text.='<method>'.$proc['name'].'</method>';
          $xml_text.='<params>
          <Session>'.$this->session.'</Session>';
          if ($params) {
            foreach ($params as $name=>$val) { 
             $xml_text.='            
            <'.$name.'>'.$val.'</'.$name.'>';
            }
           }
          $xml_text.='</params>
          </daxrequest>';
         $xml = $this->post($xml_text);

        $values = array();
        foreach ($xml->record as $i=>$r) {
           $values[$i]=$r;// $values[ (string)$r->Item ][ (string)$r->Currency ] = (string)$r->Amount;
        }
        return $values;
    }

    public function PriceListShop($name) { //,$currencys) {
        /* $xml = $this->post( $this->createXml('PriceListShop', array(
            'PriceGroup'=>$name )
        )); //*/
        //$xml = $this->_createXML('PriceListShop', array('Session'=>$this->session,'PriceGroup'=>$name)); // ТУДУ: Почему не работает предидущая!!
         $xml = $this->post('<?xml version="1.0" encoding="utf-8"?'.'>
        <daxrequest>
          <action>run</action>
          <method>PriceListShop</method>
          <params>
            <Session>'.$this->session.'</Session>
            <PriceGroup>'.$name.'</PriceGroup>
          </params>
        </daxrequest>');

        $values = array();
        foreach ($xml->record as $i=>$r) {
            $values[ (string)$r->Item ][ (string)$r->Currency ] = (string)$r->Amount;
        }
        return $values;
    }
    
    public function PriceItemShop($name, $axapta_code) { //,$currencys) {
        /* $xml = $this->post( $this->createXml('PriceListShop', array(
            'PriceGroup'=>$name )
        )); */
        $xml = $this->_createXML('PriceListShop', array('Session'=>$this->session,'Item'=>htmlspecialchars($axapta_code),'PriceGroup'=>$name)); // ТУДУ: Почему не работает предидущая!!

        $values = array();
        foreach ($xml->record as $i=>$r) {
            $values[ (string)$r->Item ][ (string)$r->Currency ] = (string)$r->Amount;
        }
        return $values;
    }

    public function PriceGroupList() {
        $xml = $this->post( $this->createXml('PriceGroupListShop', null) );
        
        $values = array();
        $index  = 1;
        foreach ($xml->record as $i=>$r) {
            $values[$index] = (string) $r->PriceGroup;
            $index++;
        }
        return $values;
    }
    
    public function getValues($lang_code='ru') {
        $xml = $this->post( $this->createXml('InventAttributeValueName', null) );
        
        $values = array();
        foreach ($xml->record as $i=>$r) {
            $valId = (string) $r->Value;
            $name    = (string) $r->Name;
            $lang    = (string) $r->Language; //RU
            
            if ($lang==$lang_code) {
                $values[$valId] = $name; }
            $this->values_names[$valId][$lang] = $name; //////////////////////// -- ?
        }
        return $values;
    }
    
    public function getProps($group,$item='') {
        $xml = $this->post( $this->createXml('InventAttributeList', array(
            'Group'=>$group,
				'Item'=>$item )
        ));
        
        $values = array();
        foreach ($xml->record as $i=>$r) {
            $item  = (string) $r->Item;
            $attr  = (string) $r->Attr;
            $value = (string) $r->Value;
            $values[$item][] = array('attr'=>$attr, 'value'=>$value);
        }
        return $values;
    }
    
    public function getTable($group) {
        $xml = $this->post( $this->createXml('InventTable', array(
            'Group'=>$group )
        ));
        
        $values = array();
        foreach ($xml->record as $i=>$r) {
            $item  = (string) $r->Item;
            $brand = '';// (string) $r->Brand;
            $avail = (string) $r->Avail;
            $article = (string) $r->Article;
            $asort = (string) $r->Assortment;
            $date  = (string) $r->CreatedDate;
            if (!$date) {
				   $date = '01.01.2001'; }
            $date = strtotime($date);
            $values[$item] = array('brand'=>$brand, 'avail'=>$avail, 'article'=>$article, 'adate' => $date, 'asort' => $this->asort_values[$asort]);
        }
        return $values;
    }
    
    
    public function getCompability($item) {
        $xml = $this->post( $this->createXml('InventCompatibility', array(
            'Item'=>$item,
            'Compatible'=>'' )
        ));
        
        $values = array();
        foreach ($xml->record as $i=>$r) {
           $values[] = array('connection_type'=>(string)$r->Compatible, 'child'=>(string)$r->ItemChild); }
        return $values;
    }
    

    public function getBrands($lang_code='ru') {
        $xml = $this->post( $this->createXml('inventBrandName', null) );
        
        $values = array();
        foreach ($xml->record as $i=>$r) {     
           $valId = (string) $r->Brand;
           $name    = (string) $r->Name;
           $lang    = (string) $r->Language; //RU
           if ($lang==$lang_code) {
              $values[$valId] = $name; }
           $this->brand_names[$valId][$lang] = $name;
        }
        return $values;
    }


    public function getDocuCert($item) {
        $xml = $this->post( $this->createXml('InventTableDocu', array(
            'Item'=>$item )
        ));

        $values = array();
        foreach ($xml->record as $i=>$r) {     
           $file     = (string) $r->FileName;
           $type     = (string) $r->Type;
           $name     = (string) $r->DocuName;
           $new      = 0;
           if ($type=='Инструкции' || $type=='Сертификат' || $type=='Файл' ) {
              if (!file_exists(ROOT.'/user/import/'.$file)) {
                 $this->getDocuFtp($item, $file);
                 $new = 1; }
              $values[] = array('file'=>$file, 'type'=>$type, 'name'=>$name, 'new'=>$new);
           }
        }
        return $values;
    }
    
    
    public function getDocuDescr($item) {
        $xml = $this->post( $this->createXml('InventTableDocu', array(
            'Item'=>$item )
        ));

        $values = array();
        foreach ($xml->record as $i=>$r) {     
           $file     = (string) $r->FileName;
           $type     = (string) $r->Type;
           $name     = (string) $r->DocuName;
           $new      = 0;
           if ( $type=='Описание' || $type=='ОписаниеUA' || $type=='ОписаниеEN' ) {
              if (!file_exists(ROOT.'/user/import/'.$file)) {
                 $this->getDocuFtp($item, $file);
                 $new = 1; }
              $values[] = array('file'=>$file, 'type'=>$type, 'name'=>$name, 'new'=>$new);
           }
        }
        return $values;
    }
    

    public function getDocuFoto($item) {
        $xml = $this->post( $this->createXml('InventTableDocu', array(
            'Item'=>$item )
        ));

        $values = array();
        foreach ($xml->record as $i=>$r) { // ---------------------------------- Сначала основное изображение (супер костыль)
           $file     = (string) $r->FileName;
           $type     = (string) $r->Type;
           $name     = (string) $r->DocuName;
           $new      = 0;
           if ($type=='Изображения') {
              if (!file_exists(ROOT.'/user/import/'.$file)) { // echo "\n---- ". ROOT.'/user/import/'.$file." ------------ \n\n";
                 $this->getDocuFtp($item, $file);
                 $new = 1; }
              $values[] = array('file'=>$file, 'type'=>$type, 'name'=>$name, 'new'=>$new);
           }
        }
        foreach ($xml->record as $i=>$r) { // ---------------------------------- Потом дополнительные (супер костыль)
           $file     = (string) $r->FileName;
           $type     = (string) $r->Type;
           $name     = (string) $r->DocuName;
           $new      = 0;
           if ($type=='Изображения дополнительные') { //echo "\n---- ". ROOT.'/user/import/'.$file." ------------ \n\n";
              if (!file_exists(ROOT.'/user/import/'.$file)) {
                 $this->getDocuFtp($item, $file);
                 $new = 1; }
              $values[] = array('file'=>$file, 'type'=>$type, 'name'=>$name, 'new'=>$new);
           }
        }
        return $values;
    }

    
    public function getDocuFtp($item, $file) {
        $xml = $this->post( $this->createXml('InventTableDocuFtp', array(
            'Item'=>$item,
            'FileName'=>$file )
        ));
    }


    public function getItem($item,$lang_code='ru') {
        $xml = $this->post( $this->createXml('InventTableName', array(
            'Item'=>$item )
        ));

        $values = array();
        foreach ($xml->record as $i=>$r) {
            $title  = mysql_real_escape_string($r->Name);
            $alias  = mysql_real_escape_string($r->Alias);
            $body   = mysql_real_escape_string($r->Txt);
            $lang   = (string) $r->Language;
            if ($lang==$lang_code) {
               $values = array('title'=>$title, 'header'=>$body, 'alias'=>$alias);
            }
            $this->item_names[$item][$lang] = array('title'=>$title, 'header'=>$body, 'alias'=>$alias);
        }
        return $values;
    } //*/

    
    public function shopCustInfo($absnum) {
        $xml = $this->post( $this->createXml('shopCustInfo', array(
            'CustAccount'=>$absnum )
        ));

        $customer = array();
        if (!$xml->error) {
            if ( isSet($xml->record) && !empty($xml->record) && count($xml->record[0])>0 ) {
               foreach ($xml->record[0] as $i=>$r) {
                   $customer[$i] = (string) $r; }
               return (object)$customer;
            }
        }
        return false;
    }
    
    
    public function custInfoShopAll($absnum, $email='', $phone='', $update=0) { // -------------- NIK
        $xml = $this->post( $this->createXml('custInfoShop', array(
            'CustAccount'=>$absnum,
            'Phone'=>$phone,
            'Email'=>$email,
            'Update'=>$update,
            'Discount'=>'' )
        ));

        if (!$xml->error) { 
            if (isSet($xml->record) && !empty($xml->record)) { 
                return $xml->record;
            } else { echo "пустой ответ для [$absnum]..."; }
        }
        return false;
    }

    
    public function shopCustInsert($absnum,$name,$email,$phone,$discount='',$address='') {
        $xml = $this->post( $this->createXml('shopCustInsert', array(
            'AbsNum'=>$absnum,
            'Name'=>$name,
            'Address'=>$address,
            'Email'=>$email,
            'Phone'=>$phone,
            'DiscountId'=>$discount )
        ));

        $priceGroup = '';
        if (!$xml->error) {
            return (string)$xml->record->CustAccount;
        } else {
		     $this->errors[] = (string) $xml->error; }
        return false;
    }
    
    
    public function shopCustUpdate($data) { //$absnum,$o_name,$o_addres,$o_email,$o_phone,$o_discount,$name,$addres,$email,$phone,$discount) {
        $xml = $this->post( $this->createXml('shopCustUpdate', array(
            'CustAccount'    => $data['absnum'],
				'OrigName'       => $data['o_name'],
				'OrigAddress'    => $data['o_addres'],
				'OrigEmail'      => $data['o_email'],
				'OrigPhone'      => $data['o_phone'],
				'OrigDiscountId' => $data['o_discount'],
				'Name'           => $data['name'],
				'Address'        => $data['addres'],
				'Email'          => $data['email'],
				'Phone'          => $data['phone'],
				'DiscountId'     => $data['discount'] )
        ));

        $customer = array();
        if (!$xml->error) {
            if (isSet($xml->record) && !empty($xml->record)) {
               foreach ($xml->record[0] as $i=>$r) {     
                   $customer[$i] = (string) $r; }
               return (object)$customer;
            }
        }
        return false;
    }
    
 
    public function setOrderQtyShop($absnum,$deliveryAddress,$paymentType,$deliveryType,$orderType,$itemAbsnum,$num,$priceGroup,$price,$orderId='',$comment='',$edrpou='') {
        $xml = $this->post( $this->createXml('setOrderQtyShop', array(
            'CustAccount'     => $absnum,
            'EDRPOU'          => $edrpou,
            'DeliveryAddress' => $deliveryAddress,
            'CustPaymMode'    => mb_substr($paymentType,0,40,'UTF-8'),
            'DeliveryMode'    => mb_substr($deliveryType,0,40,'UTF-8'),
            'SalesType'       => mb_substr($orderType,0,40,'UTF-8'),
            'SalesOrder'      => $orderId,
            'Item'            => $itemAbsnum,
            'OrderQty'        => $num,
            'PriceGroup'      => $priceGroup,
            'Price'           => $price,
            'CommentLine'     => $comment, ) //,'run','Web_Order'
        ));

        $item = array();
        if (!$xml->error) {
           foreach ($xml->record as $i=>$r) {    
               $item['orderId'] = (string) $r->SalesOrder;
               $item['Item']    = (string) $r->Item; }
           return $item;
        } else {
		     $this->errors[] = (string) $xml->error; }
        return false;
    }
    
    
    public function salesOrderConfirm($absnum,$orderId,$comment='') {
        $xml = $this->post( $this->createXml('salesOrderConfirm', array(
            'SalesOrder'=>$orderId,
            'Currency'=>'',
            'Comment'=>$comment,
            'CustAccount'=>$absnum ),'run','Web_Order'
        ));

        $axapta_code = '';
        foreach ($xml->record as $i=>$r) {
            $axapta_code = (string) $r->SalesOrder; }

        return $axapta_code;
    }
    

    public function salesOrderPaymShop($absnum,$axapta_code) {
        $xml = $this->post( $this->createXml('salesOrderPaym', array(
            'CustAccount'=>$absnum,
            'SalesOrder'=>$axapta_code ),'run','Web_Order'
        ));

        $file_code = '';
        foreach ($xml->record as $i=>$r) {     
            $file_code = (string) $r->SalesOrder; }

        if ($file_code) {
            $xml = $this->post( $this->createXml('salesOrderPaym2ftp', array(
               'CustAccount'=>$absnum,
               'SalesOrder'=>$axapta_code )
            ));
            
            $file = '';
            foreach ($xml->record as $i=>$r) {     
                $file = (string) $r->File;
            }
            return $file;
        }
        return false;
    }
    

    public function RelatedProduct($item) {
        $xml = $this->post( $this->createXml('RelatedProduct', array(
            'Item'=>$item )
        ));
        $child = '';
        foreach ($xml->record as $i=>$r) {     
            $child = (string) $r->ItemChild; }
        if ($child) {
            $childs = explode(',',$child);
        } else {
            $childs = array(); }
        return $childs;
    }
    
    
    public function PositionsOnOrder($item='') {
        $xml = $this->post( $this->createXml('PositionsOnOrder', array(
            'Item'=>$item )
        ));
        $out = array();
        foreach ($xml->record as $i=>$r) {
           $out[] = array('Item'=>(string) $r->Item, 'Val'=> (string) $r->DeliveryDate); }
        return $out;
    }
    
    
    public function MailingList() {
        $xml = $this->post( $this->createXmlClass('MailingList', null, 'run', 'Web_CRM') );
        $out = array();
        foreach ($xml->record as $i=>$r) {
           $out[] = array('ID'=>(string) $r->MailingListId, 'Name'=> (string) $r->Description); }
        return $out;
    }
    
    
    public function MailingListPerson($item='') {
        $xml = $this->post( $this->createXmlClass('MailingListPerson', array( 'MailingListId '=>$item ), 'run', 'Web_CRM') );
        $out = array();
        foreach ($xml->record as $i=>$r) {
           $out[] = array('Email'=>(string) $r->Email , 'Name'=> (string) $r->Name); }
        return $out;
    }
    
    
    public function AddNews($Header, $Summary, $Text) {
        $xml = $this->post( $this->createXml('News', array(
            'Update'=>true,
            'Header'=>$Header,
				'Summary'=>$Summary,
				'Text'=>$Text,
				'RelType' =>2)
        ));
        //print_r($xml);
        $out = array();
        //foreach ($xml->record as $i=>$r) {
           //$out[] = array('Item'=>(string) $r->Item, 'Val'=> (string) $r->DeliveryDate); }
        return $out;
    }
    
    public function AmountCurInvoices($absnum) {
        $xml = $this->post( $this->createXml('AmountCurInvoices', array(
            'CustAccount'=>$absnum ),'run','Web_Cust'
        ));
        
        //print_r($xml); die('aaa');

        $amount = '';
        foreach ($xml->record as $i=>$r) {     
            $amount = (string) $r->Amount; }
        return $amount;
    }
    
    
    public function test() { /// -------------------------------------------------------------------
        $xml = $this->post( $this->createXml('custInfoShop', null) );

        $pos = strpos($this->xml_str, 'Invalid Session');

        if ($pos) {
            return false;
        } else {
            return true;
        }
    }
    
    
    public function createXml($method, $params=null, $action='run', $class=null) {
       $xml = '<?xml version="1.0" encoding="utf-8"?'.'>
        <daxrequest>
          <action>'.$action.'</action>
          <method>'.$method.'</method>';
       if ($class) {
		    $xml .= '<class>'.$class.'</class>'; }
       $xml .= '<params>
            <Session>'.$this->session.'</Session>';
       if ($params) {
          foreach ($params AS $key=>$val) {
             $val  = htmlspecialchars($val);
             $xml .= "<$key>$val</$key>"; } }
       $xml .= '</params></daxrequest>';
       return $xml;
    }
    
    public function createXmlClass($method, $params=null, $action='run', $class='') {
       $xml = '<?xml version="1.0" encoding="utf-8"?'.'>
        <daxrequest>
          <action>'.$action.'</action>
          <method>'.$method.'</method>
          <class>'.$class.'</class>
          <params>
            <Session>'.$this->session.'</Session>';
       if ($params) {
          foreach ($params AS $key=>$val) {
             $val  = htmlspecialchars($val);
             $xml .= "<$key>$val</$key>"; } }
       $xml .= '</params></daxrequest>';
       return $xml;
    }
    
    public function _createXML($method='', $params=array()) {
       $q  = '<?xml version="1.0" encoding="utf-8"?'.'>'.
		       '<daxrequest>'.
		 	    '   <action>run</action>'.
             '   <method>'. $method .'</method>'.
             '   <params>';
       foreach($params AS $param=>$val) {
		    $q .= "<{$param}>{$val}</{$param}>\n";
		 }
		 $q .= '   </params>'.
             '</daxrequest>';
        
        return $this->post($q);
    }
    
    
    private function post($xml_request, $parse=true) { // return false; 
        global $phrase;

        $axPost = new axPost();
        $axPost->set_post_body(array(
            'xmlRequest' => $xml_request ));
            
        for ($i=0;$i<3;$i++) {
           $xml_str = $axPost->start_transfer('http://192.168.200.66:8081/service/Service.asmx/daxProcessRequest');
           if (!$xml_str) {
              echo "\n\n -------- Пустой Ответ $i -------- \n\n";
				  usleep(50000);
				  continue; }
           if(substr($xml_str, 0, 5) == "<?xml") { break; // Все ОК ------------
           } else {
              echo "\n\n -------- Ошибка в Аксапте $i -------- \n\n $xml_str \n\n"; }
           usleep(500);
        }
                            
        file_put_contents('/var/www/stat.vm.ua/cis/axapta.log' . date('Y_m_d') ."_ax_{$this->log_fle}_log.txt", "\n == ". date('d.m.Y H:i:s') ." == \n{$xml_request}\n-----------\n{$xml_str}\n", FILE_APPEND);

        if (!$xml_str) {
            $this->active = false;

            $last_time = (int) @file_get_contents(PHPECHO.'error.mail');

            if ((time()-3600)>$last_time) {
                require_once($_SERVER['DOCUMENT_ROOT'].'/lib/classes/sendemail.class.php');

                lib_sendMailErrorList(array(
                      'mail_to'=>array('marketing@vm.ua','webadmin@vm.ua','Sergey.Pinjaz@vm.ua','Anatoliy.Chigin@vm.ua'),
                      'subject'=>'ИНТЕРФЕЙС НЕДОСТУПЕН! '.date('d.m.Y H:i'),
                      'body'=>'Это письмо было отправлено, так как интерфейс для обмена данными с axapta не отвечает на запросы.' )
                );
                lib_sendMailError(array(
                      'subject'=>'ИНТЕРФЕЙС НЕДОСТУПЕН! '.date('d.m.Y H:i'),
                      'body'=>"Это письмо было отправлено, так как интерфейс для обмена данными с axapta не отвечает на запросы. <br /><br /> $xml_request <br /><br /> $xml_str ")
                );
                file_put_contents(PHPECHO.'error.mail', time());
            } 
            return false;
        }

        $this->xml_str = $xml_str;
        
        if ($parse) {
            if (strpos($xml_str, '<')===false) {
                //echo "[".$xml_request."]\n";
                //echo "[".$xml_str."]\n";
                $xml = array();
            } else {
                $xml = new SimpleXMLElement($xml_str);
					 if (strpos($xml_str,'<daxresponse />')===true) {
					    echo "\n\n -------- Пустой [daxresponse] -------------------------- \n\n"; }  
            }
            return $xml;
        } //*/
        
        return $this->xml_str;
        
        
    }
    
    function close($test=false){
       //
    }
}
