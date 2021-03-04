<!-- 
    Purpose of Script: Universal Menu Bar to be used in every page
    Written by: Michael H
    last updated: Michael 16/02/21
-->

<!-- Was neccesary to have the below css in this file as makes reference to topnav class which is created in this file and is not in scope if css
     were to be included in the websiteStyle css file -->

<style>
    /* 
       Reference : https://www.w3schools.com/howto/howto_js_topnav.asp
        Add a black background color to the top navigation 
    */
    .topnav {
        background-color: #333;
        overflow: hidden;
    }
    
    /* Style the links inside the navigation bar */
    .topnav a {
        float: left;
        color: #f2f2f2;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
    }
    
    /* Change the color of links on hover */
    .topnav a:hover {
        background-color: #ddd;
        color: black;
    }
    
    /* The below style is not used currently, as would reduce maintainability of site as I'd need to actually put the code at the top of every php file and 
       define the active page, can see if client wants it*/
    /* Add a color to the active/current link 
    .topnav a.active {
        background-color: #4CAF50;
        color: white;
    }
    */
    
</style>

<!-- h1 contains the firms name and company logo -->
<h1>  
        Dublin Party Hire 
        <img src="images/CompanyLogo.jpg" alt="Logo" style ="float:left;width:86px;height:86px;">
        
</h1>

<br>
<br>

<div class="topnav">
  <!-- <a class="active" href="HomePage.php">Home</a>              This was commented out as having a diff colour for active tab reduces site maintability-->
  <a href="HomePage.php">Home</a>
  <a href="ProductsAndPricing.php"> Products & Pricing  </a>  
  <a href="CustomerLogin.php"> Customer Login  </a> 
  <a href="StaffLogin.php"> Staff Login </a>
  <a href="ManagementLogin.php"> Manager Login </a>
  <a href="ContactUs.php"> Contact us  </a> 
  <a href="PickBookingDates.php">Just so I can test my stuff</a>
</div>