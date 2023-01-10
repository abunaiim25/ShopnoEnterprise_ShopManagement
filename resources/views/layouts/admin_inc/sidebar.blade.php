 <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
     <div class="sidenav-header">
         <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
         <a class="navbar-brand m-0" href="#">
             <span class="ms-1 font-weight-bold">SHOPNO ENTERPRISE</span>
         </a>
     </div>
     <hr class="horizontal dark mt-0">

     <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">

         @yield('sidebar_for_active')
         
     </div>

 </aside>

 <style>
     #sidenav-collapse-main {
         height: 100vh;
     }
 </style>