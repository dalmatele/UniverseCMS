<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css?v=072309092016" />
<link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.min.css?v=072309092016"/>
<link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css?v=072309092016" />
<link rel="stylesheet" type="text/css" href="../css/style.css?v=242309092016"/> <!--.-->

<script type="text/javascript" src="../js/main.js?v=072309092016"></script>

<script>
    $(document).ready(function(){
        radioChange( $('input[name="efx"]'), $('#nav'), $('#efx-name') );
        radioChange( $('input[name="ease"]'), $('#main-menu'), $('#efx-ease'));

        function radioChange(inputs, addClassTo, appendTo) {
          inputs.hide();
          inputs.on( 'change', function() { 
            inputs.each( function() {
              if ( $(this).is(':checked') ) {
                addClassTo.attr('class', $(this).val() );
                var radioName = $(this).next('span').text(); 
                appendTo.text(radioName);
              }

            });
          }); 
        }
        });
</script>
<header>
    <nav id="nav" class="ry">
    <ul id="main_menu">
         <li>
             <a href="./home.php"><i class="fa fa-home"></i> Trang chủ</a>
         </li>
         <li>
             <a href="#"><i class="fa fa-user"></i> Quản lý đơn hàng <i class="fa fa-caret-down"></i></a>
             <ul class="submenu">
                 <li><a href="./OrderManager.php">Quản lý đơn hàng</a></li>
                 <li><a href="./ExportData.php">Xuất báo cáo</a></li>
             <li>
               <a href="#0">More Items <i class="fa fa-caret-right"></i></a>
                <ul class="submenu">
                 <li><a href="#0">A Sub-Item</a></li>
                 <li>
                   <a href="#0">A Sub-Item</a>
                  </li>
                 <li>
                   <a href="#0">A Sub-Item</a>
                  </li>
               </ul>  
              </li>
           </ul>  
         </li>
         <li>
           <a href="#"><i class="fa fa-briefcase"></i> Tiện ích <i class="fa fa-caret-down"></i></a>
            <ul class="submenu">
                <li><a href="SendMarketingMail.php">Gửi thư tự động</a></li>
                <li><a href="RequestManager.php">Dịch vụ hỗ trợ</a></li>
                <li><a href="#0">Vestibulum</a></li>
                <li><a href="#0">Ipsum</a></li>
                <li><a href="#0">Consectetur</a></li>
           </ul>  
         </li>
         <li>
           <a href="#"><i class="fa fa-comment"></i> Contact Us</a>
         </li>
     </ul> 
 </nav>
</header>
